<?php


namespace Nomess\Http;


use http\Env;

/**
 * This class manage the request header and response header
 *
 * @author Romain Lavabre <webmaster@newwebsouth.fr>
 */
class HttpHeader implements RequestHeaderInterface, ResponseHeaderInterface
{
    
    // Standard Request Headers
    public const A_IM                          = 'A-IM';
    public const ACCEPT                        = 'Accept';
    public const ACCEPT_CHARSET                = 'Accept-Charset';
    public const ACCEPT_DATETIME               = 'Accept_Datetime';
    public const ACCEPT_ENCODING               = 'Accept-encoding';
    public const ACCEPT_LANGUAGE               = 'Accept-Language';
    public const ACCESS_CONTROL_REQUEST_METHOD = 'Access-Control-Request-Method';
    public const AUTHORIZATION                 = 'Authorization';
    public const CACHE_CONTROL                 = 'Cache-Control';
    public const CONNECTION                    = 'Connection';
    public const CONTENT_ENCODING              = 'Connection-Encoding';
    public const CONTENT_LENGTH                = 'Content-Length';
    public const CONTENT_MD5                   = 'Content-MD5';
    public const CONTENT_TYPE                  = 'Content-Type';
    public const COOKIE                        = 'Cookie';
    public const DATE                          = 'Date';
    public const EXPECT                        = 'Expect';
    public const FORWARDED                     = 'Forwarded';
    public const FROM                          = 'From';
    public const HOST                          = 'Host';
    public const HTTP2_SETTINGS                = 'HTTP2-Settings';
    public const IF_MODIFIED_SINCE             = 'If-Modified-Since';
    public const IF_NONE_MATCH                 = 'If-None-Match';
    public const IF_RANGE                      = 'If-Range';
    public const IF_UNMODIFIED_SINCE           = 'If-Unmodified-Since';
    public const MAX_FORWARDS                  = 'Max-Forwards';
    public const ORIGIN                        = 'Origin';
    public const PRAGMA                        = 'Pragma';
    public const PROXY_AUTHORIZATION           = 'Proxy-Authorization';
    public const RANGE                         = 'Range';
    public const REFERER                       = 'Referer';
    public const TE                            = 'TE';
    public const TRAILER                       = 'Trailer';
    public const TRANSFER_ENCODING             = 'Transfer-Encoding';
    public const USER_AGENT                    = 'User-Agent';
    public const UPGRADE                       = 'Upgrade';
    public const VIA                           = 'Via';
    public const WARNING                       = 'Warning';
    // Standard Response Header
    public const ACCESS_CONTROL_ALLOW_ORIGIN      = 'Access-Control-Allow-Origin';
    public const ACCESS_CONTROL_ALLOW_CREDENTIALS = 'Access-Control-Allow-Credentials';
    public const ACCESS_CONTROL_EXPOSE_HEADERS    = 'Access-Control-Expose-Headers';
    public const ACCESS_CONTROL_MAX_AGE           = 'Access-Control-Max-Age';
    public const ACCESS_CONTROL_ALLOW_METHODS     = 'Access-Control-Allow-Methods';
    public const ACCESS_CONTROL_ALLOW_HEADERS     = 'Access-Control_Allow-Headers';
    public const ACCEPT_PATCH                     = 'Accept-Patch';
    public const ACCEPT_RANGES                    = 'Accept-Ranges';
    public const AGE                              = 'Age';
    public const ALLOW                            = 'Allow';
    public const ALT_SVC                          = 'Alt-Svc';
    public const CONTENT_DISPOSITION              = 'Content-Disposition';
    public const CONTENT_LOCATION                 = 'Content-Location';
    public const CONTENT_RANGE                    = 'Content-Range';
    public const DELTA_BASE                       = 'Delta-Base';
    public const ETAG                             = 'ETag';
    public const EXPIRES                          = 'Expires';
    public const IM                               = 'IM';
    public const LAST_MODIFIED                    = 'Last-Modified';
    public const LINK                             = 'Link';
    public const LOCATION                         = 'Location';
    public const P3P                              = 'P3P';
    public const PUBLIC_KEY_PINS                  = 'Public-Key-Pins';
    public const RETRY_AFTER                      = 'Retry-After';
    public const SERVER                           = 'Server';
    public const SET_COOKIE                       = 'Set-Cookie';
    public const STRICT_TRANSPORT_SECURITY        = 'Strict-Transport-Security';
    public const TK                               = 'Tk';
    public const VARY                             = 'Vary';
    public const WWW_ANTENTICATE                  = 'WWW-Autenticate';
    public const X_FRAME_OPTIONS                  = 'X-Frame-Options';
    // Http codes
    public const HTTP_CONTINUE                             = 100;
    public const HTTP_SWITCHING_PROTOCOLS                  = 101;
    public const HTTP_PROCESSING                           = 102;            // RFC2518
    public const HTTP_EARLY_HINTS                          = 103;            // RFC8297
    public const HTTP_OK                                   = 200;
    public const HTTP_CREATED                              = 201;
    public const HTTP_ACCEPTED                             = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION        = 203;
    public const HTTP_NO_CONTENT                           = 204;
    public const HTTP_RESET_CONTENT                        = 205;
    public const HTTP_PARTIAL_CONTENT                      = 206;
    public const HTTP_MULTI_STATUS                         = 207;               // RFC4918
    public const HTTP_ALREADY_REPORTED                     = 208;               // RFC5842
    public const HTTP_IM_USED                              = 226;               // RFC3229
    public const HTTP_MULTIPLE_CHOICES                     = 300;
    public const HTTP_MOVED_PERMANENTLY                    = 301;
    public const HTTP_FOUND                                = 302;
    public const HTTP_SEE_OTHER                            = 303;
    public const HTTP_NOT_MODIFIED                         = 304;
    public const HTTP_USE_PROXY                            = 305;
    public const HTTP_RESERVED                             = 306;
    public const HTTP_TEMPORARY_REDIRECT                   = 307;
    public const HTTP_PERMANENTLY_REDIRECT                 = 308;  // RFC7238
    public const HTTP_BAD_REQUEST                          = 400;
    public const HTTP_UNAUTHORIZED                         = 401;
    public const HTTP_PAYMENT_REQUIRED                     = 402;
    public const HTTP_FORBIDDEN                            = 403;
    public const HTTP_NOT_FOUND                            = 404;
    public const HTTP_METHOD_NOT_ALLOWED                   = 405;
    public const HTTP_NOT_ACCEPTABLE                       = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED        = 407;
    public const HTTP_REQUEST_TIMEOUT                      = 408;
    public const HTTP_CONFLICT                             = 409;
    public const HTTP_GONE                                 = 410;
    public const HTTP_LENGTH_REQUIRED                      = 411;
    public const HTTP_PRECONDITION_FAILED                  = 412;
    public const HTTP_REQUEST_ENTITY_TOO_LARGE             = 413;
    public const HTTP_REQUEST_URI_TOO_LONG                 = 414;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE               = 415;
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE      = 416;
    public const HTTP_EXPECTATION_FAILED                   = 417;
    public const HTTP_I_AM_A_TEAPOT                        = 418;                                                      // RFC2324
    public const HTTP_MISDIRECTED_REQUEST                  = 421;                                                      // RFC7540
    public const HTTP_UNPROCESSABLE_ENTITY                 = 422;                                                      // RFC4918
    public const HTTP_LOCKED                               = 423;                                                      // RFC4918
    public const HTTP_FAILED_DEPENDENCY                    = 424;                                                      // RFC4918
    public const HTTP_TOO_EARLY                            = 425;                                                      // RFC-ietf-httpbis-replay-04
    public const HTTP_UPGRADE_REQUIRED                     = 426;                                                      // RFC2817
    public const HTTP_PRECONDITION_REQUIRED                = 428;                                                      // RFC6585
    public const HTTP_TOO_MANY_REQUESTS                    = 429;                                                      // RFC6585
    public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE      = 431;                                                      // RFC6585
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS        = 451;
    public const HTTP_INTERNAL_SERVER_ERROR                = 500;
    public const HTTP_NOT_IMPLEMENTED                      = 501;
    public const HTTP_BAD_GATEWAY                          = 502;
    public const HTTP_SERVICE_UNAVAILABLE                  = 503;
    public const HTTP_GATEWAY_TIMEOUT                      = 504;
    public const HTTP_VERSION_NOT_SUPPORTED                = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                                                // RFC2295
    public const HTTP_INSUFFICIENT_STORAGE                 = 507;                                                // RFC4918
    public const HTTP_LOOP_DETECTED                        = 508;                                                // RFC5842
    public const HTTP_NOT_EXTENDED                         = 510;                                                // RFC2774
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED      = 511;                                                // RFC6585
    
    
    /**
     * Add header to response
     *
     * @param string $header         Header (possibility to take HttpHeader::Constante)
     * @param string $value          Value of header
     * @param bool $removeLastHeader Overwrite last identical header
     * @return $this
     */
    public function set( string $header, string $value, bool $removeLastHeader = TRUE ): HttpHeader
    {
        header( $header . ': ' . $value, $removeLastHeader );
        
        return $this;
    }
    
    
    /**
     * Return response code
     *
     * @param int $code
     * @return $this
     */
    public function responseCode( int $code ): HttpHeader
    {
        http_response_code( $code );
        
        return $this;
    }
    
    
    /**
     * Return all request headers
     *
     * @return array
     */
    public function getRequestHeaders(): array
    {
        return \http\Env::getRequestHeader();
    }
    
    
    /**
     * Return all response headers
     *
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return headers_list();
    }
    
    
    /**
     * Return one request header
     *
     * @param string $header
     * @return string|null
     */
    public function getRequestHeader( string $header ): ?string
    {
        foreach( \http\Env::getRequestHeader() as $requestHeader ) {
            if( stripos( $requestHeader, mb_strtolower( $header ) . ':' ) ) {
                return $requestHeader;
            }
        }
        
        return NULL;
    }
    
    
    /**
     * Return one response header
     *
     * @param string $header
     * @return string|null
     */
    public function getResponseHeader( string $header ): ?string
    {
        foreach( headers_list() as $responseHeader ) {
            if( stripos( $responseHeader, mb_strtolower( $header ) . ':' ) ) {
                return $responseHeader;
            }
        }
        
        return NULL;
    }
    
    
    /**
     * Remove response header
     *
     * @param string $header
     * @return $this
     */
    public function remove( string $header ): HttpHeader
    {
        header_remove( $header );
        
        return $this;
    }
}


