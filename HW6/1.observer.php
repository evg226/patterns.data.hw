<?php
/**
 * abstract observer
 */
interface IApplicant{
    public function handle(string $vacancyStock,string $job):void;
}
/**
 * abstract observable subject
 */
interface IVacancyStock{
    public function attach(IApplicant $applicant);
    public function detach(IApplicant $applicant);
    public function addVacancy(string $vacancy);
    public function notify(string $vacancy);
}

/**
 * concrete observable subject
 */
class VacansyStock implements IVacancyStock {
    private $name;
    /**
     * @array IApplicants
     */
    private  $applicants;
    /**
     * @array string
     */
    private $vacancies;

    public function __construct($name)
    {
        $this->name=$name;
    }

    public function attach(IApplicant $applicant)
    {
        $this->applicants[]=$applicant;

    }

    public function detach(IApplicant $applicant)
    {
        foreach ($this->applicants as &$item){
            if($item===$applicant){
                unset($item);
            }
        }
    }

    /**
     * notify function in concrete subject
     */
    public function notify(string $vacancy)
    {
        foreach ($this->applicants as $applicant){
            if ($applicant->getJob()===$vacancy){
                $applicant->handle($this->name,$vacancy);
            }
        }
    }
    /**
     * subject state changer + auto noticer
     */
    public function addVacancy(string $vacancy)
    {
        $this->vacancies[]=$vacancy;
        echo "\nOn stock '$this->name' has been added new vacancy '$vacancy'\nPrepare to send notices for subscribers...\n";
        $this->notify($vacancy);
    }
}

/**
 * concrete observer
 */
class Applicant implements IApplicant {
    /**
     * @var string
     */
    private $name;
    private $job;
    /**
     * @array string
     */
    private $availableVacancies;

    public function __construct(string $name,string $job)
    {
        $this->name=$name;
        $this->job=$job;
    }

    /**
     * @return string
     */
    public function getJob(): string
    {
        return $this->job;
    }

    /**
     * observer handler
     */
    public function handle(string $vacancyStock,string $vacancy): void
    {
        echo "$this->name, you have got new vacancy '$vacancy' on stock '$vacancyStock'\n";
        $this->availableVacancies[]=$vacancy;
    }
}

/**
 * USING
 */
$handHunter=new VacansyStock('HandHunter');
$john=new Applicant('John','php');
$bob=new Applicant('Bob','java');
$mike=new Applicant('Mike','php');
$handHunter->attach($john);
$handHunter->attach($bob);
$handHunter->attach($mike);
$handHunter->addVacancy('php');
$handHunter->addVacancy('java');
