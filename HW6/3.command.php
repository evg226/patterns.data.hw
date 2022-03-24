<?php
/**
 * Receiver
 */
class ReceiverState{
    public TextDocument $textDocument;
    public function __construct(TextDocument $textDocument)
    {
        $this->textDocument=$textDocument;
    }

    public function action()
    {
       print_r($this->textDocument);
    }
}

/**
 * Abstract Command
 */
abstract class Command{
    public ReceiverState $state;
    public function __construct(ReceiverState $state)
    {
        $this->state=$state;
    }
    abstract public function execute(int $startPosition,int $length);
}

/**
 * Concrete Commands
 */
class Load extends Command {
    public function execute(int $startPosition,int $length)
    {
        $this->state->action();
    }
}
class Cut extends Command {
    public function execute(int $startPosition,int $length)
    {
        $this->state->textDocument->clipboard = mb_substr($this->state->textDocument->text,$startPosition,$length);
        $this->state->textDocument->text = mb_substr($this->state->textDocument->text,0,$startPosition).
            mb_substr($this->state->textDocument->text,$startPosition+$length);
        $this->state->action();
    }
}
class Copy extends Command {
    public function execute(int $startPosition,int $length)
    {
        $this->state->textDocument->clipboard = mb_substr($this->state->textDocument->text,$startPosition,$length);
        $this->state->action();
    }
}
class Paste extends Command {
    public function execute(int $startPosition,int $length)
    {
        $this->state->textDocument->text = mb_substr($this->state->textDocument->text,0,$startPosition)
            .$this->state->textDocument->clipboard
            .mb_substr($this->state->textDocument->text,$startPosition);
        $this->state->action();
    }
}

/**
 * Invoker
 */
class Editor {
    private array $commands;
    private int $activeStep=0;
    private TextDocument $textDocument;

    public function __construct(string $text)
    {
        $this->textDocument=new TextDocument($text);
    }

    function doOperation(string $operation,int $startPosition=0, int $length=0){
        $receiverState=new ReceiverState($this->textDocument);
        $command=new $operation($receiverState);
        $this->submit($command,$startPosition,$length);
        $this->textDocument = clone $command->state->textDocument;
    }

    private function submit(Command $command,int $startPosition=0, int $length=0){
        $command->execute($startPosition,$length);
        $this->commands[]=$command;
    }

    public function showCommands()
    {
         print_r($this->commands);
    }
    public function goStep(int $step=-1){
        echo "Откат на $step относительно пункта истории ". (count($this->commands)+$this->activeStep)."\n";
        $this->activeStep+=$step;
        $result = $this->commands[count($this->commands)-1+$this->activeStep]->state->textDocument;
        print_r($result);
        $this->textDocument= $result;
    }
}

/**
 * Objet for Client
 */
class TextDocument{
    public string $text;
    public string $clipboard;
    public function __construct(string $text)
    {
        $this->text=$text;
        $this->clipboard='';
    }
}

/**
 * Client (Usage)
 * Примечание - Вариант с инкапюляцией TextDocument  в Editor (Invoker).
 *               Красивее код, видел такие варианты в WikiPedia
 */

$editor = new Editor('Начальный текст файла'); //Экземпляр Invoker'a
    //Загрузка текста
$editor->doOperation('Load');
    //проверка операций
$editor->doOperation('Cut',1,1);
$editor->doOperation('Paste',0);
    //гуляем по истории
$editor->goStep(-1);
$editor->goStep(-1);
$editor->goStep(1);
    //Фиксируем выбранный пункт истории
$editor->doOperation('Load');






