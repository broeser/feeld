<?php
namespace Feeld\DataType;

use Wellid\Exception\DataType as DataTypeException;

/**
 * Allows entering a filename
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class File extends String {   
    /**
     * The accept-attribute (contains the accepted mime-type, has wildcard 
     * support e.g. for text/*)
     * 
     * @var string
     */
    protected $accept = null;
    
    /**
     * Maximum file size, if there is also a php.ini setting for file sizes
     * and the php.ini-setting is lower then $maxFilesize, the php.ini-setting
     * takes precedence
     * 
     * @var int
     */
    protected $maxFilesize = null;
    
    /**
     * Sets the accepted MIME-Type(s) for the file
     * Has wildcard support (e. g. text/*)
     * 
     * @param string $mime MIME-Type
     * @return File Returns itself for daisy-chaining
     */
    public function setAccept($mime) {
        $this->accept = $mime;
        $this->addValidator(new \Wellid\Validator\MIME($mime));
        
        return $this;
    }
    
    /**
     * Returns accepted MIME-Type
     * 
     * @return string
     */
    public function getAccept() {
        return $this->accept;
    }
    
    /**
     * Sets a maximum file size for files (in bytes)
     * 
     * @param int $size Filesize in bytes
     * @return File Returns itself for daisy-chaining
     * @throws DataType
     */
    public function setMaxFilesize($size) {
        if(!is_int($size)) {
            throw new DataTypeException('size', 'integer', $size);
        }
        
        $this->maxFilesize = $size;
        $this->addValidator(new \Wellid\Validator\Filesize($size));
        
        return $this;
    }
    
    /**
     * Returns maximum file size (in bytes)
     * 
     * @return integer
     */
    public function getMaxFilesize() {
        return $this->maxFilesize;
    }
    
    /**
     * Sets a maximum file size for files (in KB / KiB)
     * In this context, Kilobyte means "1024-byte long Kibibyte"
     * 
     * @param int $size Filesize in KiB
     * @return File Returns itself for daisy-chaining
     */
    public function setMaxFilesizeKilobytes($size) {            
        return $this->setMaxFilesize($size * 1024);
    }
    
    /**
     * Sets a maximum file size for files uploaded with this Field (in MB / MiB)
     * In this context, Megabyte means "1024²-byte long Mebibyte"
     * 
     * @param int $size
     * @return File Returns itself for daisy-chaining
     */
    public function setMaxFilesizeMegabytes($size) {
        return $this->setMaxFilesizeKilobytes($size * 1024);
    }       
}
