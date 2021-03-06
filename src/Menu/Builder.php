<?php namespace Sebastienheyd\Boilerplate\Menu;

use Lavary\Menu\Builder as LavaryMenuBuilder;
use Auth;

class Builder extends LavaryMenuBuilder
{
    private $root = [];

    /**
     * Adds an item to the menu
     *
     * @param  string  $title
     * @param  string|array  $acion
     * @return Lavary\Menu\Item $item
     */
    public function add($title, $options = '')
    {
        $title = sprintf('<span>%s</span>', $title);

        $id = isset($options['id']) ? $options['id'] : $this->id();

        $item = new Item($this, $id, $title, $options);

        if(isset($options['icon'])) {
            $item->icon($options['icon']);
        }

        if(isset($options['role'])) {
            $currentUser = Auth::user();
            if($currentUser->ability('admin', explode(',', $options['role']))) {
                $this->items->push($item);
            }
        } else {
            $this->items->push($item);
        }

        return $item;
    }

    public function addTo($id, $title, $options = '')
    {
        $parent = $this->whereId($id)->first();

        if(isset($parent)) {

            if(!isset($this->root[$parent->id])) {
                $parent->attr([ 'url' => '#', 'class' => 'treeview' ]);
                $parent->append('<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>');
                $this->root[$parent->id] = true;
            }

            $item = $parent->add($title, $options);

        } else {

            $item = $this->add($title, $options);
        }

        $item->icon('circle-o');
        return $item;
    }
}