<?php

namespace evaluaUcab\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller {

    public function portadaAction() {

        return $this->render('portada.html.twig');
    }

    public function loginAction(Request $request) {


        $session = $request->getSession();

// get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }
        return $this->render('ApplicationBundle:Default:login.html.twig');
    }

    public function inicioAction() {

        return $this->render('ApplicationBundle:Default:home.html.twig');
    }

    public function formCambiarPasswordAction() {

        return $this->render('ApplicationBundle:Default:formCambiarPassword.html.twig');

        ;
    }

    public function getResetPasswordConfirmationLink($usuario, $val) {
        //$val 0 estudiante 1 profesor

        $ch = base_convert(sha1($usuario->getId()), 16, 36);
        $url = 'http://evaluaucab.edu/app_dev.php/resetPassword/' . $val . '/' . $usuario->getId() . '/' . $ch;
        return $url;
    }

    public function checkResetPasswordLink($id, $ch) {
        $cho = base_convert(sha1($id), 16, 36);
        if ($cho === $ch)
            return true;
        return false;
    }

    public function resetPasswordAction($var, $id, $ch) {

        $check = $this->checkResetPasswordLink($id, $ch);
        if ($check) {
            if ($var == 0) { //si es estudiante
                return $this->render('ApplicationBundle:Default:formNewPassword.html.twig', array('id' => $id, 'val' => $var));
            } else { //si es profesor
                return $this->render('ApplicationBundle:Default:formNewPassword.html.twig', array('id' => $id, 'val' => $var));
            }
        }
        $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Se produjo un error');
        return $this->render('ApplicationBundle:Default:login.html.twig', array('id' => $id, 'val' => $var));
    }

    public function newPasswordAction($id, $val) {

        $newPassword = $this->getRequest()->request->get('newPassword');
        $confirmacionPassword = $this->getRequest()->request->get('confirmacionPassword');

        if ($newPassword === $confirmacionPassword) {
            $em = $this->getDoctrine()->getManager();


            if ($val == 0) {

                $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($id);
                $estudiante->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
                $encoder = $this->get('security.encoder_factory')->getEncoder($estudiante);
                $passwordCodificado = $encoder->encodePassword($newPassword, $estudiante->getSalt());
                var_dump($passwordCodificado);
                $estudiante->setPassword($passwordCodificado);
                $em->persist($estudiante);
                $em->flush();
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Cambio de contraseña exitoso!');
                return $this->render('ApplicationBundle:Default:login.html.twig');
            } else if ($val == 1) {
                $profesor = $em->getRepository('ProfesorBundle:Profesor')->find($id);
                $profesor->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
                $encoder = $this->get('security.encoder_factory')->getEncoder($profesor);
                $passwordCodificado = $encoder->encodePassword($newPassword, $profesor->getSalt());
                $profesor->setPassword($passwordCodificado);
                $em->persist($profesor);
                $em->flush();
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Cambio de contraseña exitoso!');
                return $this->render('ApplicationBundle:Default:login.html.twig');
            }
        } else {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'No se confirmó la contraseña');
            return $this->render('ApplicationBundle:Default:formNewPassword.html.twig', array('id' => $id, 'val' => $val));
        }
        var_dump($newPassword);
        var_dump($confirmacionPassword);
        var_dump($val);
        if ($newPassword === $confirmacionPassword) {
            var_dump('SIII');
        }
        return new Response();
    }

    public function cambiarPasswordAction() {

        $email = $this->getRequest()->request->get('email');
        $url = '';

        $em = $this->getDoctrine()->getManager();
        $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->findBy(array('correo' => $email));
        $profesor = $em->getRepository('ProfesorBundle:Profesor')->findBy(array('correo' => $email));

        if ($estudiante != null) {
            $url = $this->getResetPasswordConfirmationLink($estudiante[0], 0);
            var_dump($url);
            //enviarCorreo y dirigir a login
            $message = \Swift_Message::newInstance('Cambiar Contraseña');
            $message->setFrom('evalua.ucab@gmail.com');
            $message->setTo($email);

            $message->setBody(
                    '<html>' .
                    ' <head> <img src="' . $message->embed(\Swift_Image::fromPath('images/logoHeader.png')) . '" /></head>' .
                    ' <body>' .
                    ' <br><br><br><span style="font-size:18px">Estimado estudiante, usted ha soliciado cambiar su contraseña. Siga los pasos en el siguiente link </span> ' . $url .
                    ' </body>' .
                    '<br><br><br><footer>EvaluaUcab Todos los derechos reservados</footer>' .
                    '</html>', 'text/html' // Mark the content-type as HTML
            );
            $this->get('mailer')->send($message);
            
            $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Siga las instrucciones enviadas al correo para cambiar su contraseña');
            return $this->render('ApplicationBundle:Default:login.html.twig');
        } else if ($profesor != null) {
            $url = $this->getResetPasswordConfirmationLink($profesor[0], 1);
            //enviarCorreo y dirigir a login
            $message = \Swift_Message::newInstance('Cambiar Contraseña');
            $message->setFrom('evalua.ucab@gmail.com');
            $message->setTo($email);

            $message->setBody(
                    '<html>' .
                    ' <head> <img src="' . $message->embed(\Swift_Image::fromPath('images/logoHeader.png')) . '" /></head>' .
                    ' <body>' .
                    ' <br><br><br><span style="font-size:18px">Estimado profesor, usted ha soliciado cambiar su contraseña. Siga los pasos en el siguiente link </span> ' . $url .
                    ' </body>' .
                    '<br><br><br><footer>EvaluaUcab Todos los derechos reservados</footer>' .
                    '</html>', 'text/html' // Mark the content-type as HTML
            );
            $this->get('mailer')->send($message);
            $this->getRequest()->getSession()->getFlashBag()->add('notie', 'Siga las instrucciones enviadas al correo para cambiar su contraseña');
            return $this->render('ApplicationBundle:Default:login.html.twig');
        } else {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'No existe ningún usuario con este correo');
            return $this->render('ApplicationBundle:Default:formCambiarPassword.html.twig');
        }
    }

}
