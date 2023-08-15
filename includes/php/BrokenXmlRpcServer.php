<?php

namespace Nerbiz\WordClass;

/**
 * This class is used to disable XML-RPC.
 * Normally wp_xmlrpc_server::serve_request() will be used,
 * but the 'wp_xmlrpc_server_class' filter allows to change the class.
 * @see xmlrpc.php:87
 * @see wp_xmlrpc_server::serve_request()
 */
class BrokenXmlRpcServer
{
    /**
     * @return void
     */
    public function serve_request(): void
    {
        status_header(403);
    }
}
