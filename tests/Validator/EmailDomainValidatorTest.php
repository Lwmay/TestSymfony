<?php


namespace App\Tests\Validator;


use App\Repository\ConfigRepository;
use App\Validator\EmailDomain;
use App\Validator\EmailDomainValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EmailDomainValidatorTest extends TestCase
{

    public function getValidator($expectedViolation = false, $dbBlockedDomain = []) {

        // On crée un mock du repository (faux repository)
        // on désactive le constructeur originel pour qu'il ne cherche pas
        // à avoir accès à une vrai base de données.
        $repository = $this->getMockBuilder(ConfigRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        // On s'attend à avoir une méthode particulière
        $repository->expects($this->any())
            ->method('getAsArray')
            ->with('blocked_domains')
            ->willReturn($dbBlockedDomain);

        $validator = new EmailDomainValidator($repository);

        // Mock
        $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();

        // On précise que le context ai appellé la méthode buildViolation
        // On crée un faux context en créant un mock et on le récupère
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();

        if ($expectedViolation) {

            $violation->expects($this->any())->method('setParameter')->willReturn($violation);
            $violation->expects($this->once())->method('addViolation');

            // On s'attends à ce que buildViolation soit executé
            $context
                ->expects($this->once())            // On s'attend à ce que ça soit executé une seule fois
                -> method('buildViolation')         // On précise la méthode qui sera appelée
                ->willReturn($violation);

        } else {
            $context
                ->expects($this->never())            // On s'attend à ce que ça soit executé une seule fois
                -> method('buildViolation');       // On précise la méthode qui sera appelée
        }

        $validator->initialize($context);       // On envoie le context au validator

        return $validator;

    }

    public function testCatchBadDomains () {
        // On crée la contrainte avec les noms de domaines bloqués
        $constraint = new EmailDomain([
            'blocked' => ['baddomain.fr', 'aze.com']
        ]);

        // On test le validator
        $this->getValidator(true)->validate('demo@baddomain.fr', $constraint);
    }

    public function testAcceptGoodDomains () {
        // On crée la contrainte avec les noms de domaines bloqués
        $constraint = new EmailDomain([
            'blocked' => ['baddomain.fr', 'aze.com']
        ]);

        // On test le validator
        $this->getValidator(false)->validate('demo@gooddomain.fr', $constraint);
    }

    public function testBlockedDomainFromDatabase () {
        $constraint = new EmailDomain([
            'blocked' => ['baddbdomain.fr', 'aze.com']
        ]);
        $this->getValidator(true, 'baddbdomain.fr')
            ->validate('demo@baddbdomain.fr', $constraint);
    }

}