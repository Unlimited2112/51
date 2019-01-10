<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

class CKFinder_Connector_CommandHandler_CommandHandlerBase
{

    protected $_connector = NULL;
    protected $_currentFolder = NULL;
    protected $_errorHandler = NULL;

    public function __construct( )
    {
        $this->_currentFolder =& CKFinder_Connector_Core_Factory::getinstance( "Core_FolderHandler" );
        $this->_connector =& CKFinder_Connector_Core_Factory::getinstance( "Core_Connector" );
        $this->_errorHandler =& $this->_connector->getErrorHandler( );
    }

    public function getFolderHandler( )
    {
        if ( is_null( $this->_currentFolder ) )
        {
            $this->_currentFolder =& CKFinder_Connector_Core_Factory::getinstance( "Core_FolderHandler" );
        }
        return $this->_currentFolder;
    }

    protected function checkConnector( )
    {
        $_config =& CKFinder_Connector_Core_Factory::getinstance( "Core_Config" );
        if ( !$_config->getIsEnabled( ) )
        {
            $this->_errorHandler->throwError( CKFINDER_CONNECTOR_ERROR_CONNECTOR_DISABLED );
        }
    }

    protected function checkRequest( )
    {
        if ( preg_match( CKFINDER_REGEX_INVALID_PATH, $this->_currentFolder->getClientPath( ) ) )
        {
            $this->_errorHandler->throwError( CKFINDER_CONNECTOR_ERROR_INVALID_NAME );
        }
        $_resourceTypeConfig = $this->_currentFolder->getResourceTypeConfig( );
        if ( is_null( $_resourceTypeConfig ) )
        {
            $this->_errorHandler->throwError( CKFINDER_CONNECTOR_ERROR_INVALID_TYPE );
        }
        $_clientPath = $this->_currentFolder->getClientPath( );
        $_clientPathParts = explode( "/", trim( $_clientPath, "/" ) );
        if ( $_clientPathParts )
        {
            foreach ( $_clientPathParts as $_part )
            {
                if ( $_resourceTypeConfig->checkIsHiddenFolder( $_part ) )
                {
                    $this->_errorHandler->throwError( CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST );
                }
            }
        }
        if ( !is_dir( $this->_currentFolder->getServerPath( ) ) )
        {
            if ( $_clientPath == "/" )
            {
            }
            else
            {
                $this->_errorHandler->throwError( CKFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND );
            }
        }
    }

}

?>
