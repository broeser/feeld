<?php
namespace Feeld\Field\SymfonyConsole;
use \Symfony\Component\Console\Question\Question as SymfonyQuestion;
use \Feeld\Field\CommonProperties\DefaultValueInterface;

/**
 * Field that allows entering text and uses the Symfony Console Component
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SymfonyConsoleEntry extends \Feeld\Field\Entry {
    use SymfonyConsoleTrait;
    
    /**
     * Constructor
     * 
     * @param \Feeld\DataType\DataTypeInterface $dataType
     * @param mixed $id
     * @param \Feeld\Display\DisplayInterface $display
     */
    public function __construct(\Feeld\DataType\DataTypeInterface $dataType, $id = null, \Feeld\Display\DisplayInterface $display = null) {
        parent::__construct($dataType, $id, $display);
        $this->addSanitorMatchValidator();
    }
    
    /**
     * Creates a Console\Question\Question
     */
    protected function buildSymfonyQuestion() {
        $this->symfonyQuestion = new SymfonyQuestion((string)$this->display, ($this instanceof DefaultValueInterface && $this->hasDefault())?$this->getDefault():null);
        $this->setNormalizerAndValidator();
    }
}
