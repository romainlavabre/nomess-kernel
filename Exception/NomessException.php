<?php


namespace Nomess\Exception;


use Nomess\Component\Config\ConfigStoreInterface;
use Nomess\Container\Container;
use Nomess\Event\EventListenerInterface;
use Nomess\Http\HttpResponse;

class NomessException extends \ErrorException
{
    
    public function __toString()
    {
        switch( $this->severity ) {
            case E_USER_ERROR : // Si l'utilisateur émet une erreur fatale.
                $type = 'Erreur fatale';
                break;
            
            case E_WARNING : // Si PHP émet une alerte.
            case E_USER_WARNING : // Si l'utilisateur émet une alerte.
                $type = 'Attention';
                break;
            
            case E_NOTICE : // Si PHP émet une notice.
            case E_USER_NOTICE : // Si l'utilisateur émet une notice.
                $type = 'Note';
                break;
            
            default : // Erreur inconnue.
                $type = 'Erreur inconnue';
                break;
        }
        
        /** @var EventListenerInterface $eventListener */
        $eventListener = Container::getInstance()->get( EventListenerInterface::class );
        
        $eventListener->notify( EventListenerInterface::LAUNCH_EXCEPTION );
        
        return '<strong>' . $type . '</strong> : [' . $this->code . '] ' . $this->message . '<br /><strong>' . $this->file . '</strong> à la ligne <strong>' . $this->line . '</strong><br>';
    }
}

function error2exception( $code, $message, $file, $line )
{
    report( $message, $file, $line );
    throw new NomessException( $message, 0, $code, $file, $line );
}

function customException( \Throwable $e )
{
    report( $e->getMessage(), $e->getFile(), $e->getLine() );
    
    if( NOMESS_CONTEXT === 'PROD' ) {
        /** @var HttpResponse $response */
        Container::getInstance()->get( HttpResponse::class )->response_code( 500 )->show();
    } else {
        echo require ROOT . 'vendor/nomess/kernel/Tools/Exception/exception.php';
    }
    die();
}

function report( string $message, string $file, string $line )
{
    $config = Container::getInstance()->get( ConfigStoreInterface::class );
    file_put_contents(
        $config->get( ConfigStoreInterface::DEFAULT_NOMESS )['general']['path']['default_error_log'],
        "[" . date( 'd/m/Y H:i:s' ) . "] Line " . $line . ": " . $file . "\nException: " . $message . "\n---------------------------------------------------------\n",
        FILE_APPEND );
}


set_error_handler( 'NoMess\Exception\error2exception' );
set_exception_handler( 'NoMess\Exception\customException' );
