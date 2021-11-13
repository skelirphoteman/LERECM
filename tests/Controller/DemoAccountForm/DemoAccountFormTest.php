<?php

namespace App\Tests\Controller\DemoAccountForm;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Domain\DemoAccountForm\Entity\DemoAccountForm;
use App\Http\Form\AddDemoAccountFormType;


class DemoAccountFormTest extends TypeTestCase
{
    public function testAddDemoAccountForm()
    {
        $data = [
            'name' => 'Galoin Maxime',
            'email' => 'galoin.maxime@gmail.com',
            'phone' => '0760356985',
            'city' => 'Marseille',
            'find_by' => 'facebook',
            'informations' => 'test'
        ];

        $model = new DemoAccountForm();

        $form = $this->factory->create(AddDemoAccountFormType::class, $model);

        $expected = new DemoAccountForm();
        $expected->setName("Galoin Maxime");
        $expected->setEmail("galoin.maxime@gmail.com");
        $expected->setPhone("0760356985");
        $expected->setCity("Marseille");
        $expected->setFindBy("facebook");
        $expected->setInformations("test");

        $form->submit($data);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}