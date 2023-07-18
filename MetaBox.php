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
 * @version 0.8
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
     * @var string filename
     */
    private $render_tpl;
 
    /**
     * Path to the template used to display the content of the meta box.
     *
     * @var callable
     */
    private $render_cb;
 
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
     * @param string   $template
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
		$this->render_tpl = isset( $template ) ? rtrim( $template, '/' ) : '';
		$this->render_cb = $render ?? '';
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
			[ $this, 'render' ], // $this->render_tpl | $this->render_cb
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
	 * Callback passed to to add_meta_box()
	 * 
	 * @see do_meta_boxes()
     *
     * @param mixed $data_object Object that's the focus of the current screen. eg. WP_Post|WP_Comment
	 * @param array $box         Meta-box data [id, title, callback, args] (@see global $wp_meta_boxes)
     */
    public function render( $data_object, $box )
    {
        if ( ! is_readable( $this->render_tpl ) && ! is_callable( $this->render_cb ) ){
            return;
        }
 
		if ( is_callable( $this->render_cb ) ){
			call_user_func( $this->render_cb, $data_object, $box );
		} else if ( isset( $this->render_tpl ) ){
			include $this->render_tpl;
		}
    }
}