<?php
use Silex\Application;

$app = new Application();
$app['debug'] = true;
$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new \Silex\Provider\SwiftmailerServiceProvider());
$app->register(new \Silex\Provider\FormServiceProvider());
$app->register(new \Silex\Provider\TranslationServiceProvider(),
    [
        'local_fallback' => 'es',
        'translator.messages' => [],
    ]
);

$app->register(new \Arseniew\Silex\Provider\IdiormServiceProvider(),
    [
        'idiorm.db.options' => [
            'connection_string' => 'mysql:host=localhost;dbname=demoSilex',
            'username' => 'root',
            'password' => '',
        ]
    ]
);

$app->register(new \Silex\Provider\TwigServiceProvider(),
    [
        'twig.path' => __DIR__ . '/../views',
        'twig.templates' => [
            'form' => __DIR__.'/../views/form_div_layout.html.twig'
        ],
    ]
);

$app['swiftmailer.options'] = [
    'host' => 'smtp.gmail.com',
    'port' => 465,
    'username' => 'micorreo@gmail.com',
    'password' => '1234',
    'encryption' => 'ssl',
    'auth_mode' => 'login'
];

$app->match('/', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form', [])
        ->add('nombre', 'text')
        ->add('email', 'email')
        ->add('mensaje', 'textarea')
        ->getForm()
    ;

    $form->handleRequest($request);
    if ($form->isValid()) {
        $data = $form->getData();
        $inbox = $app['idiorm.db']->for_table('DemoSilex')->create();
        $inbox->nombre = $data['nombre'];
        $inbox->correo = $data['email'];
        $inbox->mensaje = $data['mensaje'];
        $inbox->save();

        $subject = 'Contacto desde landing -Silex';

        $app['mailer']->send(
            \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(['micorreoInfo@gmail.com' => 'Landing Silex'])
            ->setTo(['correoUno@gmail.com'])
            ->setBody($data['mensaje'])
        );

        return $app->redirect('/');
    }

    return $app['twig']->render('index.html.twig', [
            'form' => $form->createView(),
    ]);

})->method('POST|GET');

return $app;