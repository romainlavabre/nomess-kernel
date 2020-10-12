<?php


namespace Nomess\Http;


use Nomess\Component\Cli\Executable\RepositoryGenerator;

/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
interface ResponseHeaderInterface
{
    
    /**
     * Add header to response
     *
     * @param string $header         Header (possibility to take HttpHeader::Constante)
     * @param string $value          Value of header
     * @param bool $removeLastHeader Overwrite last identical header
     * @return ResponseHeaderInterface
     */
    public function set( string $header, string $value, bool $removeLastHeader = TRUE ): ResponseHeaderInterface;
    
    
    /**
     * Return response code
     *
     * @param int $code
     * @return ResponseHeaderInterface
     */
    public function responseCode( int $code ): ResponseHeaderInterface;
    
    
    /**
     * Return all response headers
     *
     * @return array
     */
    public function getResponseHeaders(): array;
    
    
    /**
     * Return one response header
     *
     * @param string $header
     * @return string|null
     */
    public function getResponseHeader( string $header ): ?string;
    
    
    /**
     * Remove response header
     *
     * @param string $header
     * @return ResponseHeaderInterface
     */
    public function remove( string $header ): ResponseHeaderInterface;
}
