<?php
namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Tests\ConstraintTest;
use Symfony\Component\Validator\Validation;

class UserTypeTest extends TypeTestCase
{

    /**
     * Test valid UserType submission
     *
     * @param array $formData Values for submit
     *
     * @dataProvider validUserFormData
     */
    public function testValidSubmit(array $formData)
    {
        // Create User for hydrate at submit
        $objectToCompare = new User();
        $form = $this->factory->create(UserType::class, $objectToCompare);

        // Create compare object and hydrate
        $object = new User();
        $object->setUsername($formData['username']);
        $object->setRole($formData['role']);
        $object->setEmail($formData['email']);
        $object->setPlainPassword($formData['plainPassword']['first']);

        // submit the data to the form directly
        $form->submit($formData);

        // Check if form isSynchronized
        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        // Check widgets
        $this->checkWidgets($form, $formData);
    }

    /**
     * Invalid submission
     *
     * @param array $formData      FormDatas
     * @param string $errorMessage Expected error message
     *
     * @dataProvider invalidFormData
     */
    public function testInvalidSubmit(array $formData, string $errorMessage)
    {
        // Create Form
        $objectToCompare = new User();
        $form = $this->factory->create(UserType::class, $objectToCompare);

        // Submit Form
        $form->submit($formData);

        // Check form isn't valid
        $this->assertFalse($form->isValid());

        // Count errors
        $this->assertEquals(
            1,
            $form->getErrors(true)->count()
        );

        // Check error message
        $this->assertEquals(
            $errorMessage,
            $form->getErrors(true)[0]->getMessage()
        );

        // Check widgets
        $this->checkWidgets($form, $formData);
    }

    public function invalidFormData()
    {
        // invalid pwd repeat
        $set1 = [
            'username' => 'Test',
            'plainPassword' => [
                'first' => 'aa',
                'second'=> 'a'
            ],
            'email' => 'email@test.com',
            'role'  => 'admin'
        ];

        // Invalid role
        $set2 = [
            'username' => 'NewTest',
            'plainPassword' => [
                'first' => 'xxxx',
                'second'=> 'xxxx'
            ],
            'email' => 'roleuser@gmail.com',
            'role'  => 'er'
        ];

        // Assembly data_sets and error messages
        return [
            [$set1, 'Les deux mots de passe doivent correspondre.'],
            [$set2, 'This value is not valid.']
        ];

    }

    /**
     * Valid formData arrays
     *
     * @return array userFormData
     */
    public function validUserFormData()
    {
        $set1 = [
            'username' => 'Test',
            'plainPassword' => [
                'first' => 'aa',
                'second'=> 'aa'
            ],
            'email' => 'email@test.com',
            'role'  => 'admin'
        ];

        $set2 = [
            'username' => 'NewTest',
            'plainPassword' => [
                'first' => 'xxxx',
                'second'=> 'xxxx'
            ],
            'email' => 'newtest@email.com',
            'role'  => 'user'
        ];

        return [[$set1], [$set2]];
    }

    /**
     * Overriding parent method
     * Necessary for "invalid_message" of RepeatedType
     *
     * @return array
     */
    protected function getExtensions()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return array(new ValidatorExtension(Validation::createValidator()));
    }

    private function checkWidgets(Form $form, array $dataSet)
    {
        // Create view and get children
        $view = $form->createView();
        $children = $view->children;

        // Check all widgets of formView
        foreach (array_keys($dataSet) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

    }

}