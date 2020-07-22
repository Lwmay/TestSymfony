<?php


namespace App\Tests\Validator;


use App\Validator\EmailDomain;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class EmailDomainTest extends TestCase
{
    /**
     * On vérifie que "blocked" est bien nécessaire.
     * Quand on initialise EmailDomain sans "blocked" ça doit nous renvoyer une erreur
     */
    public function testRequiredParameters () {
        $this->expectException(MissingOptionsException::class);         //On s'attend à avoir une exception.
        new EmailDomain();
    }

    /**
     * vérifier si le paramètre est bien un tableau.
     * On s'attends à avoir une exception particulière ConstraintDefenitionException
     * Ici on passe juste une chaine de caractère au lieu du tableau attendu.
     */
    public function testBadShapedBlockedParameter () {
        $this->expectException(ConstraintDefinitionException::class);
        new EmailDomain((['blocked' => 'azeaze']));
    }

    /**
     * On vérifie que c'est bien passé.
     */
    public function testOptionIsSetAsProperty () {
        $arr = ['a', 'b'];
        $domain = new EmailDomain(['blocked' => $arr]);
        $this->assertEquals($arr, $domain->blocked);
    }
}