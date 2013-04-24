<?php

/**
 * Error handler function
 *
 * @param unknown_type $errno
 * @param unknown_type $errstr
 * @param unknown_type $errfile
 * @param unknown_type $errline
 */
function ee($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    $html = '';

    switch ($errno) {
        case E_USER_ERROR:
            $html .= "<b>My ERROR</b> [$errno] $errstr<br />\n";
            $html .= "  Fatal error on line $errline in file $errfile";
            $html .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            $html .= "Aborting...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            $html .= "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            $html .= "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            $html .= "Unknown error type: [$errno] $errstr<br />\n";
            break;
    }

    if ($html) {
        e($html);

        /* Don't execute PHP internal error handler */
        return true;
    }
    return false;
}

/**
 *
 */
function handleFatalPhpError() {
   $last_error = error_get_last();
   if($last_error['type'] === E_ERROR || $last_error['type'] === E_USER_ERROR)
   {
      error_reporting(0);
      ini_set('display_errors', '0');
      ob_clean();

      e( "<strong> Error {$last_error['type']}: {$last_error['message']} |</strong>".
            " on line {$last_error['line']} of {$last_error['file']}" );
   }
   return true;
}

/**
 *
 * @param unknown_type $msg
 * @param unknown_type $backtrace
 */
function e( $msg, $backtrace = true )
{
    if (is_object($msg)) {

        //$es = $exc->getTraceAsString();
        //$ets= $exc->__toString();
        //$egc= $exc->getCode();
        //$egl= $exc->getLine();
        //$egm= $exc->getMessage();
        //$egt= $exc->getTrace();

        $msg = "<strong> Error {$msg->getCode()}: {$msg->getMessage()} |</strong>".
            " on line {$msg->getLine()}<br/><pre>".
            $msg->getTraceAsString().'</pre>';

        $calledFrom = debug_backtrace();
        $file = substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1);
        $line = $calledFrom[0]['line'];
    }

    if (ini_get('display_errors')) {
        header("Content-type: text/html; charset=utf-8");
        ?><!DOCTYPE html>
        <html>
          <head>
            <title>Error</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Bootstrap -->
            <style>body{padding:50px 200px;}<?php echo file_get_contents(dirname(dirname(__file__)).'/webroot/css/bootstrap.min.css');?></style>
          </head>
          <body>

            <div class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php echo $msg ?>
            </div>
            <?php if ($backtrace): ?>
            <pre class="prettyprint linenums"><?php
            foreach (debug_backtrace() as $k => $v):
                $file = isset($v['file'])?$v['file']:'';
                $line = isset($v['line'])?$v['line']:'';

                echo "\n".htmlspecialchars("# $file ( $line ): "
                    . (isset($v['class']) ? $v['class'] . '->' : '')
                        . $v['function']
                        .(is_array($v['args'])
                            ?'('.@implode(', ',(array)$v['args']).')'
                            :$v['args']));
            endforeach;
            ?></pre>
            <?php endif; ?>

            <script src="http://code.jquery.com/jquery.js"></script>
            <script><?php echo file_get_contents(dirname(dirname(__file__)).'/webroot/js/bootstrap.js');?></script>
          </body>
        </html>
        <?php
    }
    die();
}

/**
 *
 * @param unknown_type $msg
 */
function p()
{

    $callStack = debug_backtrace();
    $calledAt = $callStack[0];

    $callingFile = file($calledAt['file'], FILE_IGNORE_NEW_LINES);
    $callingLine = $callingFile[$calledAt['line']-1];
    $callingLine = substr($callingLine,strpos($callingLine,__METHOD__));

    preg_match('/p\((.*)\);/', $callingLine, $matches);
    if (isset($matches[1]))
        $names = explode(',', $matches[1]);
    elseif (isset($matches[0]))
        $names = explode(',', $matches[0]);
    else
        $names = $matches[0];

    ob_start();
        ?>
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo 'Called on line '. $calledAt['line'] .' of '.$calledAt['file']; ?>
            <pre class="prettyprint linenums"><?php
            $count = 0;
            foreach (func_get_args() as $k => $msg)
            {
                if ($count>0) echo "\n\n";

                echo '<b>'. trim($names[$k]) .'</b>'."\n";

                if (is_bool($msg) || is_int($msg)) var_dump($msg);
            	elseif (is_array($msg)) var_export($msg);
            	elseif (is_object($msg)) print_r($msg);
            	elseif (is_string($msg)) echo htmlspecialchars($msg);
            	elseif (!$msg) var_dump($msg);
            	else print_r($msg);

            	$count++;
            }
            ?></pre>
        </div>
        <?php
    $response = ob_get_clean();

    if (ini_get('display_errors')) {
        header("Content-type: text/html; charset=utf-8");
        ?><!DOCTYPE html>
        <html>
          <head>
            <title>Print Out</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Bootstrap -->
            <style>body{padding:50px 200px;}<?php echo file_get_contents(dirname(dirname(__file__)).'/webroot/css/bootstrap.min.css');?></style>
          </head>
          <body>
            <?php echo $response; ?>
            <script src="http://code.jquery.com/jquery.js"></script>
            <script><?php echo file_get_contents(dirname(dirname(__file__)).'/webroot/js/bootstrap.js');?></script>
          </body>
        </html>
        <?php
    }
    die();
}

/**
 *
 * @param unknown_type $msg
 */
function l( $msg='', $type='default' )
{
    $phperrorPath = dirname(__FILE__).DS."Logs/$type.log";
    $data = array(
        $_SERVER['REMOTE_ADDR'],
        date("F j Y g:i a e O"),
        $type,
        str_replace(array(',',"\r","\n"), ' ', $msg)
    );
    $data = implode(',', $data)."\r\n";

    file_put_contents($phperrorPath, $data, FILE_APPEND | LOCK_EX );
}

/**
 * Overrides
 */
set_error_handler("ee");
register_shutdown_function('handleFatalPhpError');
Configure::write('Error.handler', 'ee');

