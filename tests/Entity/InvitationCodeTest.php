<?php


namespace App\Tests\Entity;


use App\Entity\InvitationCode;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class InvitationCodeTest extends WebTestCase
{

    use FixturesTrait;

    /**
     * Permet de récupérer une entité
     */
    public function getEntity (): InvitationCode
    {
        return (new InvitationCode())
            ->setCode('12345')
            ->setDescription('Description de test')
            ->setExpireAt(new \DateTime());
    }

    /**
     * Assertion personalisée
     */
    public function assertHasErrors(InvitationCode $code, int $number =0) {

        self::bootKernel();

        // On récupére une liste d'erreur
        $errors = self::$container->get('validator')->validate($code);

        // On vérifie que l'on a aucune erreur
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error){
            $messages[] = $error->getPropertyPath() . ' => ' .
                $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * Permet de tester si un entité valide reste valide.
     */
    public function testValidEntity ()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    /**
     * Permet de tester une entity non valide
     */
    public function testInvalidCodeEntity ()
    {
        $this->assertHasErrors($this->getEntity()->setCode('1a345'), 1);
        $this->assertHasErrors($this->getEntity()->setCode('1345'), 1);
    }

    public function testInvalidBlankCodeEntity()
    {
        $this->assertHasErrors($this->getEntity()->setCode(''), 1);
    }

    public function testInvalidBlankDescription()
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }

}