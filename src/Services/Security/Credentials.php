<?php


namespace App\Services\Security;


use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\Request;

class Credentials
{

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $email;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $password;

    public function __construct(?string $email, ?string $password)
    {
        $this->email = $email;
        $this->password = $password;
        return $this;
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function createFromRequest(Request $request)
    {
        if ($request->get('password') !== $request->get('confirm_password') && $request->get('password')) {
            return null;
        }

        if (!$request->get('email')) {
            return null;
        }

        $this->email = $request->get('email');
        $this->password = $request->get('password');

        return $this;
    }


}