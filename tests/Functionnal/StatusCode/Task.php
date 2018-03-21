<?php
namespace Test\Functionnal\StatusCode;

class Task extends StatusCode
{

    public function testTaskListWithAnon() {}
    public function testTaskListWithUser() {}
    public function testTaskListWithAdmin() {}

    public function testTaskCreateWithAnon() {}
    public function testTaskCreateWithUser() {}
    public function testTaskCreateWithAdmin() {}
    
    public function testTaskToggleWithAnon() {}
    public function testTaskToggleWithUser() {}
    public function testTaskToggleWithAdmin() {}

    public function testTaskDeleteWithAnon() {}

    public function testAnonTaskDeleteWithUser() {}
    public function testAnonTaskDeleteWithAdmin() {}

    public function testOwnedTaskDeleteWithUser() {}
    public function testOwnedTaskDeleteWithAdmin() {}

    public function testNotOwnedTaskDeleteWithUser() {}
    public function testNotOwnedTaskDeleteWithAdmin() {}

}