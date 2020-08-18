@foreach($childs as $child)
    <?php $stageLevel = $stage + 1; ?>
    <tr class="stage stage-{{ $stageLevel }} parent-{{ $child->categoryId }} child-{{ $parentId }}">
        <?php
        $childDash = '';
        for ($x = 0; $x < $stage; $x++) {
            $childDash .= '-';
        }
        ?>
        <th onclick="expand(this)">
            {{ $child->categoryId }} 
            <?php if (count($child->childs) > 0) { echo '<span class="expand-ico"><i class="fa fa-arrow-circle-right"></i></span>'; } ?>
        </th>
        <th>{{ $childDash . ' ' . $child->name }}</th>
        <th>{{ $child->description }}</th>
        <th width="5%">
            <div>
                <a href="{{route('songs-category.edit', $child->categoryId)}}"><span><i class="fa fa-edit"></i></span></a>
                <a href="#"><span><i class="fa fa-trash"></i></span></a>
            </div>
        </th>
            <th>
                <?php
                if($child->status == 1){
                    echo '<center><a href="javascript:void(0)" form="noForm" class="blue song-category-status-toggle " data-id="'.$child->categoryId.'"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                }else{
                    echo '<center><a href="javascript:void(0)" form="noForm" class="blue song-category-status-toggle " data-id="'.$child->categoryId.'"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                }
                ?>
            </th>
    </tr>
    @if(count($child->childs) > 0)
        @include('SongsCategory::manageChild',['childs' => $child->childs, 'stage' => $stageLevel, 'parentId' => $child->categoryId])
    @endif
@endforeach