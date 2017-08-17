<?php

namespace Rougin\Slytherin\Template;

class TwigRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Template\RendererInterface
     */
    protected $renderer;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * Sets up the renderer.
     *
     * @return void
     */
    public function setUp()
    {
        class_exists('Twig_Environment') || $this->markTestSkipped('Twig is not installed.');

        $directory = __DIR__ . '/../Fixture/Templates';

        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($directory));

        $this->renderer = new \Rougin\Slytherin\Template\TwigRenderer($this->twig);
    }

    /**
     * Tests the render() method.
     *
     * @return void
     */
    public function testRenderMethod()
    {
        $expected = 'This is a text from a template.';

        $this->assertEquals($expected, $this->renderer->render('test', array(), 'php'));
    }

    /**
     * Tests the render() method with data.
     *
     * @return void
     */
    public function testRenderMethodWithData()
    {
        $expected = 'This is a text from a template.';

        $data = array('name' => 'template');

        $rendered = $this->renderer->render('test-with-twig-data', $data, 'php');

        $this->assertEquals($expected, $rendered);
    }

    /**
     * Tests the render() method with a global variable.
     *
     * @return void
     */
    public function testRenderMethodWithGlobals()
    {
        $expected = 'This is a text from a template.';

        $renderer = new \Rougin\Slytherin\Template\TwigRenderer($this->twig);

        $renderer->addGlobal('name', 'template');

        $this->assertEquals($expected, $renderer->render('test-with-twig-data', array(), 'php'));
    }
}
