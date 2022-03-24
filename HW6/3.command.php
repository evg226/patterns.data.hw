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

    public function submit(Command $command,int $startPosition=0, int $length=0){
        $command->execute($startPosition,$length);
        $this->commands[]=$command;
    }

    public function showCommands()
    {
         print_r($this->commands);
    }
    public function getTextDocument(int $step=-1):TextDocument{
        echo "Откат на $step относительно пункта истории ". (count($this->commands)+$this->activeStep)."\n";
        $this->activeStep+=$step;
        $result = $this->commands[count($this->commands)-1+$this->activeStep]->state->textDocument;
        print_r($result);
        return $result;
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
 * Примечание - Клиентский объект TextDocument реализован на Client.
 *              Было бы красивее, если бы textDocument инкапсулирован в Editor.
 *              ОДнако UML -диаграмма патерна требует, чтобы Client был отделен от Invoker и взаимодействовал через Command
 */

$editor=new Editor(); //Экземпляр Invoker'a

//Функция для создания сборки
function doOperation(TextDocument $textDocument, string $operation,int $startPosition=0, int $length=0):TextDocument{
    global $editor;
    $receiverState=new ReceiverState($textDocument);
    $command=new $operation($receiverState);
    $editor->submit($command,$startPosition,$length);
    return clone $command->state->textDocument;
}
    //Загрузка текста (можно написать код и из файла)
$textDocument=new TextDocument('Начальный текст файла');
$textDocument=doOperation($textDocument,'Load');
    //проверка операций
$textDocument=doOperation($textDocument,'Cut',1,1);
$textDocument=doOperation($textDocument,'Paste',0);
    //гуляем по истории
$textDocument=$editor->getTextDocument(-1);
$textDocument=$editor->getTextDocument(-1);
$textDocument=$editor->getTextDocument(1);
    //Фиксируем выбранный пункт истории
$textDocument=doOperation($textDocument,'Load');






