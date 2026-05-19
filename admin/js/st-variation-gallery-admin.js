(function($){
	function saveGallery($container){
		var variationId=$container.data('variation-id'),
			ids=$container.find('.st-gallery-image').map(function(){return $(this).data('id');}).get();
		$.post(stVarGalleryAdmin.ajaxurl,{
			action:'st_save_variation_gallery',
			nonce:stVarGalleryAdmin.nonce,
			variation_id:variationId,
			gallery:ids.join(',')
		});
	}

	$(document).on('click','.st-add-gallery-images',function(e){
		e.preventDefault();
		var $container=$(this).closest('.st-variation-gallery-container'),
			$list=$container.find('.st-variation-gallery-images'),
			frame=wp.media({title:'Select Images',multiple:true,library:{type:'image'}});
		frame.on('select',function(){
			var existing=$list.find('.st-gallery-image').map(function(){return String($(this).data('id'));}).get();
			frame.state().get('selection').each(function(a){
				var id=a.id,url=a.attributes.sizes.thumbnail?a.attributes.sizes.thumbnail.url:a.attributes.url;
				if(existing.indexOf(String(id))===-1){
					$list.append('<li class="st-gallery-image" data-id="'+id+'"><img src="'+url+'"/><a href="#" class="st-remove-image">&times;</a></li>');
				}
			});
			saveGallery($container);
		}).open();
	});

	$(document).on('click','.st-remove-image',function(e){
		e.preventDefault();
		var $item=$(this).closest('.st-gallery-image'),
			$container=$item.closest('.st-variation-gallery-container');
		$item.remove();
		saveGallery($container);
	});

	$(document).on('woocommerce_variations_loaded',function(){
		$('.st-variation-gallery-images').sortable({
			update:function(){
				saveGallery($(this).closest('.st-variation-gallery-container'));
			}
		});
	});
})(jQuery);
