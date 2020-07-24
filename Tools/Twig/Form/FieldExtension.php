<?php


namespace Nomess\Tools\Twig\Form;


use Twig\TwigFunction;

class FieldExtension extends \Twig\Extension\AbstractExtension
{
    
    private bool           $bootstrap           = TRUE;
    private bool           $first               = TRUE;
    private ?string        $last_id             = NULL;
    private ?string        $last_label          = NULL;
    private ?string        $last_type           = NULL;
    private array          $reflection_property = array();
    private ValueExtension $value_extension;
    
    
    public function __construct( ValueExtension $value_extension )
    {
        $this->value_extension = $value_extension;
    }
    
    
    public function getFunctions()
    {
        return [
            new TwigFunction( 'input', [ $this, 'input' ] ),
            new TwigFunction( 'select', [ $this, 'select' ] ),
            new TwigFunction( 'textarea', [ $this, 'textarea' ] ),
            new TwigFunction( 'label', [ $this, 'label' ] ),
            new TwigFunction( 'compose', [ $this, 'compose' ] ),
            new TwigFunction( 'bootstrap', [ $this, 'bootstrap' ] ),
            new TwigFunction( 'first', [ $this, 'first' ] )
        ];
    }
    
    
    public function bootstrap( bool $used )
    {
        $this->bootstrap = $used;
    }
    
    public function first(bool $first = TRUE): void
    {
        $this->first = $first;
    }
    
    
    public function label( array $options = [] ): void
    {
        $this->setLabel( $this->engineLabel( $options ) );
    }
    
    
    public function input( array $options = [], $valueExtension = NULL ): void
    {
        $this->show(
            $this->addBootstrap(
                $this->addLabel(
                    $this->engineInput( $options, $valueExtension )
                )
            )
        );
    }
    
    
    public function select( array $option_select = [], array $data = [], $valueExtension = NULL ): void
    {
        
        $this->show(
            $this->addBootstrap(
                $this->addLabel(
                    $this->engineSelect( $option_select, $data, $valueExtension )
                )
            )
        );
    }
    
    
    public function textarea( array $options = [], $valueExtension = NULL ): void
    {
        $this->show(
            $this->addBootstrap(
                $this->addLabel(
                    $this->engineTextarea( $options, $valueExtension )
                )
            )
        );
    }
    
    
    public function compose( $objects, array $toCompose, ?array $search = NULL ): array
    {
        if( is_object( $objects ) ) {
            $objects = [ $objects ];
        }
        
        $key      = key( $toCompose );
        $value    = current( $toCompose );
        $composed = array();
        $data     = NULL;
        
        if( !empty( $objects ) ) {
            
            if( !empty( $search ) ) {
                $data = $this->takeDataForCompose( $objects, $search );
            } else {
                $data = $objects;
            }
            
            
            $array_key   = array();
            $array_value = array();
            
            preg_match_all( '/prop\((.+)\)/', $key, $key_output );
            
            if( isset( $key_output[1] ) ) {
                $iteration = count( $key_output[1] );
                
                for( $i = 0; $i < $iteration; $i++ ) {
                    $array_key["prop(" . $key_output[1][$i] . ")"] = $key_output[1][$i];
                }
            }
            
            preg_match_all( '/prop\(([a-zA-Z0-9_-]+)\)/', $value, $value_output );
            
            if( isset( $value_output[1] ) ) {
                $iteration = count( $value_output[1] );
                
                for( $i = 0; $i < $iteration; $i++ ) {
                    $array_value["prop(" . $value_output[1][$i] . ")"] = $value_output[1][$i];
                }
            }
            
            foreach( $data as $object ) {
                $composed_key   = $key;
                $composed_value = $value;
                $classname      = get_class( $object );
                
                foreach( $array_key as $toReplace => $propertyName ) {
                    $reflectionProperty = $this->getReflectionProperty( $classname, $propertyName );
                    
                    $composed_key = str_replace( $toReplace, $reflectionProperty->getValue( $object ), $composed_key );
                }
                
                foreach( $array_value as $toReplace => $propertyName ) {
                    $reflectionProperty = $this->getReflectionProperty( $classname, $propertyName );
                    
                    $composed_value = str_replace( $toReplace, $reflectionProperty->getValue( $object ), $composed_value );
                }
                
                $composed[$composed_key] = $composed_value;
            }
        }
        
        return $composed;
    }
    
    
    private function takeDataForCompose( $contains, array $search ): array
    {
        $data = array();
        
        if( is_array( $contains ) ) {
            foreach( $contains as $value ) {
                if( is_array( $value ) ) {
                    $data = array_merge( $data, $this->takeDataForCompose( $value, $search ) );
                } elseif( is_object( $value ) ) {
                    $classname = get_class( $value );
                    
                    if( array_key_exists( $classname, $search ) ) {
                        $reflectionProperty = $this->getReflectionProperty( $classname, $search[$classname] );
                        
                        if( $reflectionProperty->isInitialized( $value ) ) {
                            $data = array_merge( $data, $this->takeDataForCompose( $reflectionProperty->getValue( $value ), $search ) );
                        }
                    } else {
                        $data[] = $value;
                    }
                }
            }
        } elseif( is_object( $contains ) ) {
            
            $classname = get_class( $contains );
            
            if( array_key_exists( $classname, $search ) ) {
                $reflectionProperty = $this->getReflectionProperty( $classname, $search[$classname] );
                
                if( $reflectionProperty->isInitialized( $contains ) ) {
                    $data = array_merge( $data, $this->takeDataForCompose( $reflectionProperty->getValue( $contains ), $search ) );
                }
            } else {
                $data[] = $contains;
            }
        }
        
        return $data;
    }
    
    
    private function addLabel( string $field ): string
    {
        $label = NULL;
        
        if( $this->hasLabel() ) {
            $label = str_replace( '<!--for-->', 'for="' . $this->getId() . '"', $this->getLabel() );
            
            if( $this->labelBefore() ) {
                return $label . $field;
            }
            
            return $field . $label;
        }
        
        $this->last_type  = NULL;
        $this->last_id    = NULL;
        $this->last_label = NULL;
        
        return $field;
    }
    
    
    private function addBootstrap( string $content ): string
    {
        if( $this->bootstrap ) {
            if( $this->first ) {
                $this->first = FALSE;
                
                return '<div class="form-group">' . $content . '</div>';
            } else {
                return '<div class="form-group mt-5">' . $content . '</div>';
            }
        }
        
        return $content;
    }
    
    
    private function setId( string $id ): void
    {
        $this->last_id = $id;
    }
    
    
    private function getId(): ?string
    {
        $id            = $this->last_id;
        $this->last_id = NULL;
        
        return $id;
    }
    
    
    private function setLabel( string $label ): void
    {
        $this->last_label = $label;
    }
    
    
    private function getLabel(): string
    {
        $label            = $this->last_label;
        $this->last_label = NULL;
        
        return $label;
    }
    
    
    private function hasLabel(): bool
    {
        return !is_null( $this->last_label );
    }
    
    
    private function labelBefore(): bool
    {
        $type            = $this->last_type;
        $this->last_type = NULL;
        
        return $type !== 'checkbox' && $type !== 'radio';
    }
    
    
    private function show( string $content ): void
    {
        echo $content;
    }
    
    
    private function engineInput( array $options, $valueExtension ): string
    {
        $id = NULL;
        
        if( array_key_exists( 'name', $options ) ) {
            $id = 'form_' . $options['name'];
            $this->setId( $id );
        }
        
        $metadata = [
            'type'     => 'text',
            'class'    => $this->bootstrap ? 'form-control' : NULL,
            'required' => TRUE,
            'id'       => $id
        ];
        
        if( array_key_exists( 'type', $options ) && $options['type'] === 'file' ) {
            if( $this->bootstrap ) {
                $metadata['class'] = 'form-control-file';
            }
        }
        
        $metadata        = array_merge( $metadata, $options );
        $this->last_type = $metadata['type'];
        
        $content = '<input ';
        
        foreach( $metadata as $attribute => $value ) {
            if( !is_null( $value ) && $value !== FALSE ) {
                $content .= "$attribute=\"$value\" ";
            } elseif( $value === TRUE ) {
                $content .= "$attribute ";
            }
        }
        
        if( !isset( $metadata['value'] ) ) {
            if( is_null( $valueExtension ) ) {
                $valueExtension = [
                    0 => $metadata['name'],
                ];
            } elseif( is_array( $valueExtension ) ) {
                $tmp = [
                    0 => $metadata['name'],
                ];
                
                $valueExtension = array_merge( $tmp, $valueExtension );
            }
        } else {
            $valueExtension = FALSE;
        }
        
        $content .= ( is_array( $valueExtension ) ? 'value="' . call_user_func_array( [ $this->value_extension, 'value' ], $valueExtension ) . '"' : NULL ) . '>';
        
        return $content;
    }
    
    
    private function engineSelect( array $options, array $data, $valueExtension ): string
    {
        $id = NULL;
        
        if( array_key_exists( 'name', $options ) ) {
            $id = 'form_' . $options['name'];
            $this->setId( $id );
        }
        
        $metadata = [
            'class'    => $this->bootstrap ? 'custom-select' : NULL,
            'required' => TRUE,
            'id'       => $id
        ];
        
        $metadata = array_merge( $metadata, $options );
        
        $content = '<select ';
        
        foreach( $metadata as $attribute => $value ) {
            if( !is_null( $value ) && $value !== FALSE ) {
                $content .= "$attribute=\"$value\" ";
            } elseif( $value === TRUE ) {
                $content .= "$attribute ";
            }
        }
        
        $content .= ">\n\t";
        
        if( is_null( $valueExtension ) ) {
            $valueExtension = [
                0 => $metadata['name'],
                1 => NULL
            ];
        } elseif( is_array( $valueExtension ) ) {
            $tmp = [
                0 => $metadata['name'],
                1 => NULL
            ];
            
            $valueExtension = array_merge( $tmp, $valueExtension );
        }
        
        foreach( $data as $key => $value ) {
            if( $key !== 'void' ) {
                if( is_array( $valueExtension ) ) {
                    $valueExtension[1] = $key;
                }
                
                $content .= "<option " . ( is_array( $valueExtension ) ? call_user_func_array( [ $this->value_extension, 'select' ], $valueExtension ) : NULL ) . " value=\"$key\">$value</option>\n";
            } else {
                $content .= "<option value=\"\">$value</option>\n";
            }
        }
        
        $content .= '</select>';
        
        return $content;
    }
    
    
    private function engineLabel( array $options ): string
    {
        $content = '<label ';
        
        foreach( $options as $key => $value ) {
            if( $key !== 'value' ) {
                $content .= "$key=\"$value\" ";
            }
        }
        
        if( array_key_exists( 'value', $options ) ) {
            $content .= '<!--for-->>' . $options['value'] . '</label>';
        } else {
            $content .= '></label>';
        }
        
        return $content;
    }
    
    
    private function engineTextarea( array $options, $valueExtension ): string
    {
        $id = NULL;
        
        if( array_key_exists( 'name', $options ) ) {
            $id = 'form_' . $options['name'];
            $this->setId( $id );
        }
        
        $metadata = [
            'required' => TRUE,
            'id'       => $id,
            'class'    => 'form-control'
        ];
        
        $metadata = array_merge( $metadata, $options );
        
        $content = '<textarea ';
        
        foreach( $metadata as $attribute => $value ) {
            if( !is_null( $value ) && $value !== FALSE && $attribute !== 'value' ) {
                $content .= "$attribute=\"$value\" ";
            } elseif( $value === TRUE ) {
                $content .= "$attribute ";
            }
        }
        
        if( is_null( $valueExtension ) ) {
            $valueExtension = [
                0 => $metadata['name'],
            ];
        } elseif( is_array( $valueExtension ) ) {
            $tmp = [
                0 => $metadata['name'],
            ];
            
            $valueExtension = array_merge( $tmp, $valueExtension );
        }
        
        return $content .= '>' . ( isset( $metadata['value'] ) ? $metadata['value'] : ( is_array( $valueExtension ) ? call_user_func_array( [ $this->value_extension, 'value' ], $valueExtension ) : NULL ) ) . '</textarea>';
    }
    
    
    private function getReflectionProperty( string $classname, string $propertyName ): \ReflectionProperty
    {
        if( isset( $this->reflection_property[$classname][$propertyName] ) ) {
            return $this->reflection_property[$classname][$propertyName];
        }
        
        $reflectionProperty = new \ReflectionProperty( $classname, $propertyName );
        
        if( !$reflectionProperty->isPublic() ) {
            $reflectionProperty->setAccessible( TRUE );
        }
        
        return $this->reflection_property[$classname][$propertyName] = $reflectionProperty;
    }
}
