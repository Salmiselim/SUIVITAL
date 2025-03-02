<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private UrlGeneratorInterface $urlGenerator;
    private SessionInterface $session;

    public function __construct(UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }

        if (in_array('ROLE_DOCTOR', $user->getRoles(), true)) {
            if (!$user->isVerified()) { // Ensure this method exists in User entity
                $this->session->getFlashBag()->add('warning', 'Your account is not yet verified. Please wait for admin approval.');
            }
            return new RedirectResponse($this->urlGenerator->generate('app_doctor_dashboard'));
        }

        if (in_array('ROLE_PATIENT', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_patient_dashboard'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}
