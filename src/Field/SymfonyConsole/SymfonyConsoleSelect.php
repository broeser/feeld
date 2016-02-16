<?php
namespace Feeld\Field\SymfonyConsole;
use Symfony\Component\Console\Question\ChoiceQuestion as SymfonyQuestion;
use \Feeld\Field\CommonProperties\DefaultValueInterface;

/**
 * Field that allows to select one or multiple of given options using the 
 * Symfony Console Component
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SymfonyConsoleSelect extends \Feeld\Field\Select {
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
     * Creates a Console\Question\ChoiceQuestion
     */
    protected function buildSymfonyQuestion() {
        $this->symfonyQuestion = new SymfonyQuestion((string)$this->display, array_keys($this->getOptions()), ($this instanceof DefaultValueInterface && $this->hasDefault())?$this->getDefault():null);
        if($this instanceof \Feeld\Field\CommonProperties\MultipleChoiceInterface && $this->isMultipleChoice()) {
            $this->symfonyQuestion->setMultiselect(true);
        }
        $this->setNormalizerAndValidator();
    }
}
