<?php

# Collectd Interface plugin

require_once 'conf/common.inc.php';
require_once 'type/GenericIO.class.php';
require_once 'inc/collectd.inc.php';

# LAYOUT - Collectd 5

$obj = new Type_GenericIO($CONFIG);
$obj->data_sources = array('denied_req', 'denied_resp','error_req','error_conn','error_resp');
$obj->ds_names = array(
	'denied_req' => 'Request denied',
	'denied_resp' => 'Response denied',
	'error_req' => 'Request error',
	'error_conn' => 'Connection error',
	'error_resp' => 'Reponse error'
);
$obj->colors = array(
	'denied_req' => '0000ff',
	'denied_resp' => '00b000',
	'error_req' => 'ff00ff',
	'error_conn' => '0bb000',
	'error_resp' => '00ba00'
);
$obj->width = $width;
$obj->heigth = $heigth;
$obj->rrd_format = '%5.1lf%s';

$instance = $CONFIG['version'] < 5 ? 'tinstance' : 'pinstance';

// $obj->logtrace($obj->args['type']);

#haproxy_session_rates
#haproxy_errors
#haproxy_sessions
#haproxy_status
#haproxy_traffic

switch($obj->args['type']) {
	case 'haproxy_status':
		$obj->rrd_title = sprintf('Haproxy status (%s)', $obj->args[$instance]);
		$obj->rrd_vertical = 'Errors per second';
	break;

	case 'haproxy_traffic':
		$obj->rrd_title = sprintf('Haproxy Traffic (%s)', $obj->args[$instance]);
		$obj->rrd_vertical = 'Errors per second';
	break;

	case 'haproxy_errors':
		$obj->rrd_title = sprintf('Haproxy Errors (%s)', $obj->args[$instance]);
		$obj->rrd_vertical = 'Errors per second';
	break;

	case 'haproxy_session_rates':
		$obj->rrd_title = sprintf('Haproxy Traffic (%s)', $obj->args[$instance]);
		$obj->rrd_vertical = sprintf('%s per second', ucfirst($CONFIG['network_datasize']));
		$obj->scale = $CONFIG['network_datasize'] == 'bits' ? 8 : 1;
	break;

	case 'haproxy_sessions':
		$obj->rrd_title = sprintf('Haproxy Packets (%s)', $obj->args[$instance]);
		$obj->rrd_vertical = 'Packets per second';
	break;
}

collectd_flush($obj->identifiers);
$obj->rrd_graph();
