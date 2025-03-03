<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use App\Entity\Doctor;
use App\Entity\Patient;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private UrlGeneratorInterface $urlGenerator;
    private RequestStack $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    public function onAuthenticationSuccess(\Symfony\Component\HttpFoundation\Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();
        $session = $this->requestStack->getSession();

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }

        if (in_array('ROLE_DOCTOR', $user->getRoles(), true)) {
            if ($user instanceof Doctor) {
                // Store Doctor ID in session
                $session->set('doctor_id', $user->getId());

                if (!$user->isVerified()) {
                    $session->set('flash_warning', 'Your account is not yet verified.');
                }
            }
            return new RedirectResponse($this->urlGenerator->generate('app_doctor_dashboard'));
        }

        if (in_array('ROLE_PATIENT', $user->getRoles(), true)) {
            if ($user instanceof Patient) {
                // Store Patient ID in session
                $session->set('patient_id', $user->getId());
            }
            return new RedirectResponse($this->urlGenerator->generate('app_patient_dashboard'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}
