<?php
namespace Feeld\Field\SymfonyConsole;
use \Symfony\Component\Console\Question\Question as SymfonyQuestion;
use \Feeld\Field\CommonProperties\DefaultValueInterface;

/**
 * Description of Question
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SymfonyConsoleEntry extends \Feeld\AbstractField {
    /**
     * The SymfonyQuestion used internally
     * 
     * @var \Symfony\Component\Console\Question\Question 
     */
    protected $symfonyQuestion;
    
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
     * Creates and returns a Console\Question\Question
     *
     * @return \Symfony\Component\Console\Question\Question 
     */
    protected function buildSymfonyQuestion() {
        $this->symfonyQuestion = new SymfonyQuestion((string)$this->display, ($this instanceof DefaultValueInterface && $this->hasDefault())?$this->getDefault():null);
        $this->symfonyQuestion->setNormalizer(function($value) {
            return $this->getSanitizer()->filter($value);
        });
        
        $this->symfonyQuestion->setValidator(function($value) {
            $validationResultSet = $this->validateValue($value);
            if($validationResultSet->hasErrors()) {
                $this->clearValidationResult();
                throw new \Exception(implode(PHP_EOL, $validationResultSet->getErrorMessages()));
            }

            return $this->getFilteredValue();
        });
        
        $this->symfonyQuestion->setMaxAttempts(null);
        
        return $this->symfonyQuestion;
    }
    
    /**
     * Due to the coupling of question string (display) and Question object,
     * each time the Display is refreshed, the Symfony question has to be 
     * regenerated
     */
    public function refreshDisplay() {
        parent::refreshDisplay();
        $this->buildSymfonyQuestion();
    }
}
