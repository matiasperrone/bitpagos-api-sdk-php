<?php

namespace BitPagos\Core;

/**
 * Simple Logging Manager.
 * This does an error_log for now
 * Potential frameworks to use are PEAR logger, log4php from Apache
 */
class LoggingManager
{
	
	/**
	 * Default Logging Level
	 */
	const DEFAULT_LOGGING_LEVEL = 0;
	
	/**
	 * Logger Name
	 *
	 * @var string
	 */
	private $loggerName;
	
	/**
	 * Log Enabled
	 *
	 * @var bool
	 */
	private $isLoggingEnabled;
	
	/**
	 * Configured Logging Level
	 *
	 * @var int|mixed
	 */
	private $loggingLevel;
	
	/**
	 * Configured Logging File
	 *
	 * @var string
	 */
	private $loggerFile;

	/**
	 * Returns the singleton object
	 *
	 * @param string $loggerName        	
	 * @return $this
	 */
	public static function getInstance($loggerName = __CLASS__)
	{
		$instance = new self();
		$instance->setLoggerName( $loggerName );
		return $instance;
	}

	/**
	 * Sets Logger Name.
	 * Generally defaulted to Logging Class
	 *
	 * @param string $loggerName        	
	 */
	public function setLoggerName($loggerName = __CLASS__)
	{
		$this->loggerName = $loggerName;
	}

	/**
	 * Default Constructor
	 */
	public function __construct()
	{
		// To suppress the warning during the date() invocation in logs, we would default the timezone to GMT.
		if (! ini_get( 'date.timezone' ))
		{
			date_default_timezone_set( 'GMT' );
		}
		
		$config = ConfigManager::getInstance()->getConfigHashmap();
		
		$this->isLoggingEnabled = ( array_key_exists( 'log.LogEnabled', $config ) && $config['log.LogEnabled'] == '1' );
		
		if ($this->isLoggingEnabled)
		{
			$this->loggerFile = ( $config['log.FileName'] ) ? $config['log.FileName'] : ini_get( 'error_log' );
			$loggingLevel = strtoupper( $config['log.LogLevel'] );
			$this->loggingLevel = ( isset( $loggingLevel ) && defined( __NAMESPACE__ . "\\LoggingLevel::$loggingLevel" ) ) ? constant( __NAMESPACE__ . "\\LoggingLevel::$loggingLevel" ) : LoggingManager::DEFAULT_LOGGING_LEVEL;
		}
	}

	/**
	 * Default Logger
	 *
	 * @param string $message        	
	 * @param int $level        	
	 */
	private function log($message, $level = LoggingLevel::INFO)
	{
		if ($this->isLoggingEnabled)
		{
			$config = ConfigManager::getInstance()->getConfigHashmap();
			// Check if logging in live
			if (array_key_exists( 'mode', $config ) && $config['mode'] == 'live')
			{
				// Live should not have logging level above INFO.
				if ($this->loggingLevel >= LoggingLevel::INFO)
				{
					// If it is at Debug Level, throw an warning in the log.
					if ($this->loggingLevel == LoggingLevel::DEBUG)
					{
						error_log( "[" . date( 'd-m-Y h:i:s' ) . "] " . $this->loggerName . ": ERROR\t: Not allowed to keep 'Debug' level for Live Environments. Reduced to 'INFO'\n", 3, 
								$this->loggerFile );
					}
					// Reducing it to info level
					$this->loggingLevel = LoggingLevel::INFO;
				}
			}
			
			if ($level <= $this->loggingLevel)
			{
				error_log( "[" . date( 'd-m-Y h:i:s' ) . "] " . $this->loggerName . ": $message\n", 3, $this->loggerFile );
			}
		}
	}

	/**
	 * Log Error
	 *
	 * @param string $message        	
	 */
	public function error($message)
	{
		$this->log( "ERROR\t: " . $message, LoggingLevel::ERROR );
	}

	/**
	 * Log Warning
	 *
	 * @param string $message        	
	 */
	public function warning($message)
	{
		$this->log( "WARNING\t: " . $message, LoggingLevel::WARN );
	}

	/**
	 * Log Info
	 *
	 * @param string $message        	
	 */
	public function info($message)
	{
		$this->log( "INFO\t: " . $message, LoggingLevel::INFO );
	}

	/**
	 * Log Fine
	 *
	 * @param string $message        	
	 */
	public function fine($message)
	{
		$this->log( "FINE\t: " . $message, LoggingLevel::FINE );
	}

	/**
	 * Log Fine
	 *
	 * @param string $message        	
	 */
	public function debug($message)
	{
		$this->log( "DEBUG\t: " . $message, LoggingLevel::DEBUG );
	}
}
