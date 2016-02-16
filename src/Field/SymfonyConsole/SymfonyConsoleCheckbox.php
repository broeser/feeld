<?php
namespace Feeld\Field\SymfonyConsole;
use Symfony\Component\Console\Question\ConfirmationQuestion as SymfonyQuestion;
use \Feeld\Field\CommonProperties\DefaultValueInterface;

/**
 * A checked/unchecked-Question using the Symfony-Console-Component
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SymfonyConsoleCheckbox extends \Feeld\Field\Checkbox {
    use SymfonyConsoleTrait;
    
    /**
     * RegExp to match true/checked state (default is at least first character 
     * of true/checked Option description, case-insensitive)
     * 
     * @var string
     */
    protected $trueRegExp;
    
    /**
     * Constructor
     * 
     * @param \Feeld\DataType\DataTypeInterface $dataType
     * @param array $options
     * @param mixed $id
     * @param string $trueRegExp
     * @param \Feeld\Display\DisplayInterface $display
     * @throws \Wellid\Exception\DataType
     */
    public function __construct(\Feeld\DataType\DataTypeInterface $dataType = null, array $options = array('yes' => 1, 'no' => 0), $id = null, $trueRegExp = null, \Feeld\Display\DisplayInterface $display = null) {
        parent::__construct($dataType, $options, $id, $display);
        $this->addSanitorMatchValidator();
        
        if(is_null($trueRegExp)) {
            $trueRegExp = '/^'.strtolower(substr($this->getTrueOption(),0,1)).'/i';
        } elseif(!is_string($trueRegExp)) {
            throw new \Wellid\Exception\DataType('trueRegExp', 'string', $trueRegExp);
        }
        
        $this->trueRegExp = $trueRegExp;
    }
    
    /**
     * Creates and returns a Console\Question\ConfirmationQuestion
     */
    protected function buildSymfonyQuestion() {
        $this->symfonyQuestion = new SymfonyQuestion((string)$this->display, ($this instanceof DefaultValueInterface && $this->hasDefault())?$this->getDefault():true, $this->trueRegExp);
        $this->setNormalizerAndValidator();
    }
}
