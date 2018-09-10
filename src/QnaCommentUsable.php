<?php
namespace Akasima\RichShop;

use Akasima\RichShop\Models\Product;
use Xpressengine\Plugins\Comment\CommentUsable;
use Xpressengine\Routing\InstanceRoute;
use Xpressengine\User\UserInterface;

class QnaCommentUsable implements CommentUsable
{
    /** @var  Product */
    protected $product;

    /**
     * QnaCommentUsable constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Returns unique identifier
     * 상품의 옵션 데이터에서 id를 가져와 댓글과 연결하는 id로 사용
     *
     * @return string
     */
    public function getUid()
    {
        $options = $this->product->options;
        return $options[0]->id;
    }

    /**
     * Returns instance identifier
     *
     * @return mixed
     */
    public function getInstanceId()
    {
        return 'rich_shop_qna';
    }

    /**
     * Returns author
     *
     * @return UserInterface
     */
    public function getAuthor()
    {
        return $this->product->getAuthor();
    }

    /**
     * Returns the link
     *
     * @param InstanceRoute $route route instance
     * @return string
     */
    public function getLink(InstanceRoute $route)
    {
        return $this->getLink($route);
    }
}
