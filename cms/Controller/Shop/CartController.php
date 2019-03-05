<?php


namespace Cms\Controller\Shop;


use Cms\Controller\PublicController;
use Cms\Model\Cart\Cart;
use Engine\Core\Config\Config;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\DI\DI;
use Engine\Helper\Helper;
use Engine\Helper\Message;
use Engine\Helper\Obj;
use Exception;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use stdClass;


/**
 * Class CartController
 * @package Cms\Controller
 */
class CartController extends PublicController
{

    /**
     * @var Cart
     */
    private $cart;



    /**
     * CartController constructor.
     *
     * @param $di
     *
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->cart = $this->load->model('Cart');
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function checkout()
    {
        $this->twig->load('cart/checkout.twig');
        echo $this->twig->render('cart/checkout.twig', $this->data);


    }



    /**
     * @throws ExceptionSafeMySQL
     * @throws Exception
     */
    public function getCheckout()
    {
        $outObj = new stdClass();
        $outObj->error_code = 0;
        $outObj->total = 0;
        $outObj->count = $this->cart->count($this->getAppSessionId());
        $outObj->items = $this->cart->getCheckouts(
            $this->getAppSessionId(),
            $this->lang
        );

        // Casting data types
        foreach ($outObj->items as $key => $item){
            $outObj->items[$key] = cast($item);
        }

        // calc total amount
        foreach ($outObj->items as $key => $item){
            $outObj->total = $outObj->total + $item['amount'];
        }


        // TODO: In the future, you need to solve the issue of removal in a separate function.
        $imagine = new Imagine();
        foreach ($outObj->items as $key => $item){

            if (!isset($item['image'])){
                $item['image'] = Config::item('DEFAULT_IMAGE');
            }

            $dir_img_miniatures = Config::item('DIR_IMG_MINIATURES');

            if (!file_exists(ROOT_DIR . $dir_img_miniatures))
                mkdir(ROOT_DIR . $dir_img_miniatures, 755);

            $file_img_miniature = $dir_img_miniatures . '/' . md5($item['image']) . '.' . extractFileExt($item['image']);

            if (!file_exists(ROOT_DIR . $file_img_miniature)){
                if (is_file(ROOT_DIR . $item['image'])){
                    $image = $imagine->open(ROOT_DIR . $item['image']);
                    $size  = $image->getSize();
                    $new_size = $size->heighten(100);

                    $image->resize(new Box($new_size->getWidth(), $new_size->getHeight()));
                    $image->save(ROOT_DIR . $file_img_miniature);
                }
            }

            $outObj->items[$key]['image'] = $file_img_miniature; // minnows
        }

        Helper::echoJsonUtf8($outObj);
        exit();
    }



    /**
     * Add product to cart
     */
    public function add()
    {
        $obj = new Obj($this->request->jsonObj());

        try{
            $this->db()->beginTransaction();

            $this->cart->add([
                'product_id' => $obj->property('product_id', null),
                'customer_id' => $this->getCustomerId(),
                'app_session_id' => $this->getAppSessionId(),
            ]);

            $this->db()->endTransaction();

            Message::success($this->lpm->translate('catalog', 'product_added_to_cart'));
        }catch (ExceptionSafeMySQL $e){

        }
    }



    public function remove()
    {
        $id = $this->request->get('id', false);

        try{
            $this->cart->remove($id);

            Message::success($this->lpm->translate('shop', 'item_removed_from_cart'));
        }catch (ExceptionSafeMySQL $e){

        }
    }



    public function quantityInc()
    {
        $id = $this->request->get('id', false);
        try{
            $this->cart->quantityInc($id);

            Message::success('OK');
        }catch (ExceptionSafeMySQL $e){

        }
    }



    public function quantityDec()
    {
        $id = $this->request->get('id', false);
        try{
            $this->cart->quantityDec($id);

            Message::success('OK');
        }catch (ExceptionSafeMySQL $e){

        }
    }



    /**
     * @throws ExceptionSafeMySQL
     */
    public function count()
    {
        $outObj = new stdClass();
        $outObj->error_code = 0;
        $outObj->count = $this->cart->count($this->getAppSessionId());

        Helper::echoJsonUtf8($outObj);
        exit();
    }
}