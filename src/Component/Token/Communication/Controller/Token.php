<?php declare(strict_types=1);

namespace App\Component\Token\Communication\Controller;

use App\Component\Demo\Business\FakeInfoInterface;
use App\Component\Token\Business\TokenFacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class Token extends AbstractController
{
    /**
     * @var TokenFacadeInterface
     */
    private $tokenFacade;

    /**
     * @param TokenFacadeInterface $tokenFacade
     */
    public function __construct(TokenFacadeInterface $tokenFacade)
    {
        $this->tokenFacade = $tokenFacade;
    }

    public function generate()
    {
        $url = $this->tokenFacade->generateUrlToRefreshToken();
        return $this->redirect($url);
    }

    public function saveToken()
    {
        $request = Request::createFromGlobals();
        $this->tokenFacade->saveToken($request->query->get('code'));
        $refreshToken = $this->tokenFacade->getToken();
        if (empty($refreshToken)) {
            throw $this->createNotFoundException('Refresh token can not generated');
        }

        return $this->json([
            'success' => true,
            'refresh_token' => $refreshToken
        ]);

    }
}