<?php


namespace App\Controller;

use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
//    /**
//     * @Route("/registration", name="registration")
//     */
    #[Route(path: '/registration', name: 'app_registration')]
    public function registration(Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $regform = $this->createFormBuilder()
            ->add('email', TextType::class, ['label' => 'correo electronico'])
            ->add('password', RepeatedType::class, ['type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repetir contraseña'],
            ])
            ->add('registro', SubmitType::class)->getForm();

        $regform->handleRequest($request);
        if ($regform->isSubmitted()){
            $registrationValues = $regform->getData();
            $user = new User(Uuid::uuid4(), $registrationValues['email']);
            $user->setPassword($passwordEncoder->hashPassword($user, $registrationValues['password']));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('security/login.html.twig', ['last_username' => null, 'error' => null]);
        }

        return $this->render('registration/registration.html.twig', ['regform' => $regform->createView()]);
    }
}