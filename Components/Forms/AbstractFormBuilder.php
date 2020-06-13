<?php

namespace NoMess\Components\Forms;

use NoMess\Components\Component;
use NoMess\Exception\WorkException;

abstract class AbstractFormBuilder extends Component
{

    private const COMPONENT_CONFIG      = ROOT . 'App/config/components/Form.php';
    private const PATH_CACHE            = ROOT . 'Web/public/inc/forms/';

    protected const TWIG_ENGINE            = 'twig';
    protected const DEFAULT_ENGINE         = 'php';


    private array $config;

    private array $groupId = array();
    private ?string $lastId;
    private string $engine;

    private string $content = '';



    public final function __construct()
    {
        parent::__construct();
        $this->config = require self::COMPONENT_CONFIG;
    }


    /**
     * Specify the environment
     *
     * @param string $engine
     * @param string $template
     * @throws WorkException
     */
    public function bindEnvironment(string $engine, string $template = 'bootstrap'): void
    {
        $this->engine = $engine;

        if(array_key_exists($template, $this->config)){
            $this->config = $this->config[$template];
        }else{
            throw new WorkException('FormBuilder encountered an error: the template configuration with key "' . $template . '" doesn\'t exists');
        }
    }

    /**
     * Create form
     *
     * @param array $attributes
     * @throws WorkException
     */
    public function makeForm(array $attributes): void
    {
        $config = $this->getConfigAttribute('form');

        $result = $this->mergedAttributes($config, $attributes);



        $this->buildForm($result, true);
        $this->content .= "\n\t" . '<input type="hidden" name="_token" value="' . ($this->engine === 'php' ? '$_SESSION[\'token\']' : '{{ _token }}') . '">';
    }

    /**
     * Start group
     *
     * @param array|null $attributes
     * @return $this
     * @throws WorkException
     */
    public function startGroup(?array $attributes = null): AbstractFormBuilder
    {
        $config = $this->getConfigAttribute('group');

        $result = $this->mergedAttributes($config, $attributes);

        $this->buildGroup($result, true);
        return $this;
    }


    /**
     * Stop group
     *
     * @throws WorkException
     */
    public function endGroup(): void
    {
        $this->buildGroup(null, false);
    }


    /**
     * Create an label, the for attribute will be the id attribute for the next element
     *
     * @param array|null $attributes
     * @return $this
     * @throws WorkException
     */
    public function label(?array $attributes = null): AbstractFormBuilder
    {
        $config = $this->getConfigAttribute('label');
        $this->setId($attributes);

        $result = $this->mergedAttributes($config, $attributes);

        $this->buildLabel($result);

        return $this;
    }


    /**
     * Create an input element
     *
     * @param array $attributes
     * @param bool $completeValue
     * @return $this
     * @throws WorkException
     */
    public function input(array $attributes, bool $completeValue = true): AbstractFormBuilder
    {
        $this->vType($attributes);
        $config = $this->getConfigAttribute('input', $attributes['type']);

        $result = $this->mergedAttributes($config, $attributes);

        $this->buildInput($result, $completeValue);
        return $this;
    }


    /**
     * Create an textarea element
     *
     * @param array $attributes
     * @param bool $completeValue
     * @return $this
     * @throws WorkException
     */
    public function textarea(array $attributes, bool $completeValue = true): AbstractFormBuilder
    {
        $config = $this->getConfigAttribute('textarea');

        $result = $this->mergedAttributes($config, $attributes);

        $this->buildTextarea($result, $completeValue);
        return $this;
    }


    /**
     * Create an select element
     *
     * @param array $attributes
     * @param array $option
     * @param bool $completeValue
     * @param \Closure|null $closure
     * @return $this
     * @throws WorkException
     */
    public function select(array $attributes, ?array $option, bool $completeValue = true, ?\Closure $closure = null): AbstractFormBuilder
    {
        $config = $this->getConfigAttribute('select');

        $result = $this->mergedAttributes($config, $attributes);

        $this->buildSelect($result, $option, $completeValue, $closure);

        return $this;
    }



    /**
     * Close form
     *
     * @throws WorkException
     */
    public function endForm(): void
    {
        $this->buildForm(null, false);
        $this->register();
    }


    /**
     * Add arbitrary code
     *
     * @param string $content
     * @return $this
     */
    public function arbitraryCode(string $content): AbstractFormBuilder
    {
        $this->content .= $content;

        return $this;
    }



    /**
     * Valid the presence  of type attribute, return an Exception if doesn't exists
     *
     * @param array $attributes
     * @throws WorkException
     */
    private function vType(array $attributes): void
    {
        if(!array_key_exists('type', $attributes)){

            if(array_key_exists('name', $attributes)) {
                throw new WorkException('FormBuilder encountered an error: attribute "type" not found for input::name=' . $attributes['name']);
            }else{
                throw new WorkException('FormBuilder encountered an error: attribute "type" not found for an input');
            }
        }
    }


    /**
     * Return configuration of field, if value of field name isn't array, it's the attributes
     *
     * @param string $stamp
     * @param string|null $type
     * @return array|null
     */
    private function getConfigAttribute(string $stamp, ?string $type = null): ?array
    {

        if(isset($this->config[$stamp][$type])){
            return $this->config[$stamp][$type];
        }elseif(isset($this->config[$stamp])){
            return $this->config[$stamp];
        }

        return null;
    }


    /**
     * @param array|null $attributes
     * @param bool $start
     * @throws WorkException
     */
    private function buildForm(?array $attributes, bool $start): void
    {

        $field = null;

        if($start === true) {
            $field = '<form' . $this->addAttributes($attributes, 'form', false);
        }else{
            $field = '</form>';
        }


        $this->content .= "\n" . $field;
    }


    /**
     * @param array $attributes
     * @param bool $completeValue
     * @throws WorkException
     */
    private function buildInput(array $attributes, bool $completeValue): void
    {

        $field = '<input';

        $field .= $this->addAttributes($attributes, 'input', $completeValue, false);

        $this->content .= "\n\t\t" . $field;

    }


    /**
     * @param array|null $attributes
     * @param array|null $options
     * @param bool $completeValue
     * @param \Closure $closure
     * @throws WorkException
     */
    private function buildSelect(?array $attributes, ?array $options, bool $completeValue, ?\Closure $closure): void
    {

        $field = '<select';

        $field .=  $this->addAttributes($attributes, 'select', false);

        if($options !== null){
            foreach ($options as $content => $attributesOption){

                $field .= "\n\t\t\t<option";

                if($attributesOption !== null){
                    foreach ($attributesOption as $attribute => $value){

                        $field .= ' ' . $attribute . '="' . $value . '"';

                        if($completeValue === true){
                            if(isset($attributesOption['value'])) {
                                $field .= $this->completeOption($attributesOption['value']);
                            }else{
                                throw new WorkException('FormBuilder encountered an error: attribute "value" must be specified for completes value with POST for option balise');
                            }
                        }
                    }
                }

                $field .= '>' . $content . '</option>';
            }
        }elseif($closure !== null){
            $field .= $closure();
        }

        $field .= "\n\t\t</select>";

        $this->content .= "\n\t\t" . $field;
    }


    /**
     * @param array|null $attributes
     * @param bool $completeValue
     * @throws WorkException
     */
    private function buildTextarea(?array $attributes, bool $completeValue): void
    {
        $field = '<textarea';

        $field .= $this->addAttributes($attributes, 'textarea', $completeValue, true);

        $field .= '</textarea>' . "\n";

        $this->content .= "\n\t\t" . $field;
    }


    /**
     * @param array|null $attributes
     * @throws WorkException
     */
    private function buildLabel(?array $attributes): void
    {
        $field = '<label' . $this->addAttributes($attributes, 'label', false, true) . '</label>';

        $this->content .= "\n\t\t" . $field;
    }


    /**
     * @param array|null $attributes
     * @param bool $start
     * @throws WorkException
     */
    private function buildGroup(?array $attributes, bool $start): void
    {
        if($start === true){
            $field = '<div' . $this->addAttributes($attributes, 'group', false);
        }else{
            $field = '</div>';
        }

        $this->content .= "\n\t" . $field;
    }



    /**
     * Add the attributes
     *
     * @param array|null $attributes
     * @param string $provider
     * @param bool $completeValue
     * @param bool $putEndvalue
     * @return string|null
     * @throws WorkException
     */
    private function addAttributes(?array $attributes, string $provider, bool $completeValue, bool $putEndvalue = false): ?string
    {

        $content = '';

        if(isset($attributes['type']) && $attributes['type'] === 'submit'){
                $content .= ' name="buttonsubmit"';
        }

        if($provider === 'form'){
            $content .= ' onsubmit="buttonsubmit.disabled = true; return true;"';
        }

        if($attributes !== null) {
            foreach ($attributes as $attribute => $value) {
                if ($attribute !== 'value') {
                    $content .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }

        if($provider !== 'label') {
            $content .= $this->getId($attributes);
        }

        if($putEndvalue === true) {
            if($attributes !== null) {
                if ($completeValue === false && array_key_exists('value', $attributes)) {
                    $content .= '>' . $attributes['value'];
                } elseif($completeValue === true) {
                    $content .= '>' . $this->completeValue($attributes);
                }
            }else{
                throw new WorkException('FormBuilder encountered an error: for push value to the end, array attribute with "name" attribute or "value" attribute must be declared');
            }
        }elseif($completeValue === true){
            if($attribute !== null) {
                $content .= ' value="' . $this->completeValue($attributes) . '">';
            }else{
                throw new WorkException('FormBuilder encountered an error: for create an completed value with POST variable, array attribute with "name" attribute must be declared');
            }
        }elseif($attribute !== null && array_key_exists('value', $attributes)){
            $content .= ' value="' . $attributes['value'] . '">';
        }else{
            $content .= '>';
        }



        return $content;
    }


    /**
     * Add the post value
     *
     * @param array $attributes
     * @return string
     * @throws WorkException
     */
    private function completeValue(array $attributes): string
    {

        if(!array_key_exists('name', $attributes) || empty($attributes['name'])){
            throw new WorkException('FormBuilder encountered an error: cannot create an value for "value" attribute, attribute name is not found or empty');
        }

        $defaultValue = null;

        if(!isset($attributes['value'])){
            if($this->engine === 'twig'){
                $defaultValue = ' }}';
            }else{
                $defaultValue = ' : null ?>';
            }
        }else{
            if(strpos($attributes['value'], '{{') !== false && strpos($attributes['value'], '}}') !== false && $this->engine === 'twig'){
                $defaultValue = ' : ' . str_replace(['{{', '}}'], '', $attributes['value']) . '}}';
            }elseif((bool)preg_match('/\$[A-Za-z0-9-_]+/', $attributes['value']) && $this->engine === 'php'){
                $defaultValue = ' : ' . $attributes['value'] . ' ?>';
            }else{
                if($this->engine === 'twig'){
                    $defaultValue = ' : "' . $attributes['value'] . '" }}';
                }else{
                    $defaultValue = ' : "' . $attributes['value'] . '" ?>';
                }
            }
        }

        $name = $attributes['name'];


        if($this->engine === 'twig'){
            return '{{ POST.' . $name . ' is defined ? POST.' . $name . $defaultValue;
        }else{
            return '<?php isset($_POST[\'' . $name . '\']) ? $_POST[\'' . $name . '\']' . $defaultValue;
        }

    }


    /**
     * @param string $name
     * @return string
     */
    private function completeOption(string $name): string
    {
        if($this->engine === 'twig'){
            return ' {{ POST.' . $name . ' is defined and POST.' . $name . ' == ' . $name . ' ? "selected" }}';
        }else{
            return ' <?php (isset($_POST["' . $name . '"]) && $_POST["' . $name . '"] === "' . $name . ') ? "selected" : ""';
        }
    }


    /**
     * Create the id
     *
     * @param array|null $attributes
     */
    private function setId(?array $attributes): void
    {
        if($attributes !== null && array_key_exists('for', $attributes)){
            $this->lastId = $attributes['for'];
        }
    }

    /**
     * Return id
     *
     * @param string $content
     * @return string|null
     * @throws WorkException
     */
    private function getId(?array $attributes): ?string
    {
        if(isset($this->lastId)){

            if(array_key_exists('id', $attributes) && $attributes['id'] !== $this->lastId) {
                throw new WorkException('FormBuilder encountered an error: conflict: id must be  "' . $this->lastId . "' but you have specified an other id, remove the for attribute of label or of parameter");
            }

            if(!empty($this->groupId)) {
                foreach ($this->groupId as $id) {
                    if ($id === $this->lastId) {
                        throw new WorkException('FormBuilder encountered an error: conflict = mulptiple id "' . $this->lastId . '"');
                    }
                }
            }

            $this->groupId[] = $this->lastId;
            $content = ' id="' . $this->lastId . '"';
            $this->lastId = null;


            return $content;
        }

        return null;
    }


    private function register(): void
    {
        file_put_contents(self::PATH_CACHE . str_replace('App\\Forms\\', '', get_class($this)) . '.' . $this->engine, $this->content);
    }


    /**
     * @param array|null $config
     * @param array|null $attributes
     * @return array|null
     */
    private function mergedAttributes(?array $config, ?array $attributes): ?array
    {
        if($config !== null && $attributes !== null) {
            return array_replace($config, $attributes);
        }elseif($config !== null){
            return $config;
        }elseif($attributes !== null){
            return $attributes;
        }

        return null;
    }
}