<?

function test_redis_con(){
$configs = include('config.php');

set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    ping($configs->{'rds_server'},$configs->{'rds_port'},0.2);
    return true;


} catch (Exception $e) {

    return false;
}



}

function ping($host, $port, $timeout) {
    $tB = microtime(true);
    $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
    if (!$fP) { return "down"; }
    $tA = microtime(true);
    return round((($tA - $tB) * 1000), 0)." ms";
}




?>