<?php


namespace Nomess\Http;


/**
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
interface RequestHeaderInterface
{
    
    /**
     * Add header to response
     *
     * @param string $header         Header (possibility to take HttpHeader::Constante)
     * @param string $value          Value of header
     * @param bool $removeLastHeader Overwrite last identical header
     * @return RequestHeaderInterface
     */
    public function set( string $header, string $value, bool $removeLastHeader = TRUE ): RequestHeaderInterface;
    
    
    /**
     * Return all request headers
     *
     * @return array
     */
    public function getRequestHeaders(): array;
    
    
    /**
     * Return one request header
     *
     * @param string $header
     * @return string|null
     */
    public function getRequestHeader( string $header ): ?string;
}
