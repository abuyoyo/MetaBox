<?php
namespace WPHelper;

use function wp_parse_args;
use function add_action;
use function add_meta_box;
/**
 * MetaBox
 *
 * Object-Oriented WordPress meta box creator.
 * 
 * @author abuyoyo
 * @version 0.5
 */
class MetaBox
{
    /**
     * Screen context where the meta box should display.
     *
     * @var string
     */
    private $context;
 
    /**
     * The ID of the meta box.
     *
     * @var string
     */
    private $id;
 
    /**
     * The display priority of the meta box.
     *
     * @var string
     */
    private $priority;
 
    /**
     * Screens where this meta box will appear.
     *
     * @var string[]
     */
    private $screens;
 
    /**
     * Path to the template used to display the content of the meta box.
     *
     * @var string
     */
    private $template;
 
    /**
     * The title of the meta box.
     *
     * @var string
     */
    private $title;
	
     /**
     * Hook where this meta box will be added.
     *
     * @var string
     */
    private $hook;
 
    /**
     * Array of $args to be sent to callback function's second parameter
     *
     * @var array
     */
    private $args;
 
    /**
     * Constructor.
     *
     * @param string   $id
     * @param string   $emplate
     * @param string   $title
     * @param string   $context
     * @param string   $priority
     * @param string[] $screens
     */
    public function __construct($options)
    {
		// should throw error if required fields (id, title) not given
		// template is actually optional
		
		$defaults = [
			'context' => 'advanced',
			'priority' => 'default',
			'screens' =>  [],
			'args' => null,
			'hook' => 'add_meta_boxes',
		];
		
		$options = wp_parse_args( $options, $defaults );
		extract($options);
 
        $this->context = $context;
        $this->id = $id;
        $this->priority = $priority;
        $this->screens = $screens;
        $this->template = rtrim($template, '/');
        $this->title = $title;
        $this->hook = $hook;
        $this->args = $args;
    }
	
	/**
     * Add metabox at given hook.
     *
     * @return void
     */
	public function add()
	{
		add_action( $this->hook, [ $this, 'wp_add_metabox' ] );	
	}
	
	public function wp_add_metabox(){
		add_meta_box(
			$this->id,
			$this->title,
			[ $this, 'render' ],
			$this->screens,
			$this->context,
			$this->priority,
			$this->args
		);
	}
 
    /**
     * Get the callable that will render the content of the meta box.
     *
     * @return callable
     */
    public function get_callback()
    {
        return [ $this, 'render' ];
    }
 
    /**
     * Get the screen context where the meta box should display.
     *
     * @return string
     */
    public function get_context()
    {
        return $this->context;
    }
 
    /**
     * Get the ID of the meta box.
     *
     * @return string
     */
    public function get_id()
    {
        return $this->id;
    }
 
    /**
     * Get the display priority of the meta box.
     *
     * @return string
     */
    public function get_priority()
    {
        return $this->priority;
    }
 
    /**
     * Get the screen(s) where the meta box will appear.
     *
     * @return array|string|WP_Screen
     */
    public function get_screens()
    {
        return $this->screens;
    }
 
    /**
     * Get the title of the meta box.
     *
     * @return string
     */
    public function get_title()
    {
        return $this->title;
    }
 
    /**
     * Render the content of the meta box using a PHP template.
     *
     * @param WP_Post $post
	 * @param array metabox - id, title, callback, args array
     */
    public function render($object, $metabox)
    {
        if ( ! is_readable( $this->template ) ){
            return;
        }
 
        include $this->template;
    }
}