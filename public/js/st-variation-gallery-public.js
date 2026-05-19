(function($){
	function dipToWhite($gallery,callback){
		var $featured=$gallery.find('.st-gallery-featured'),
			$img=$gallery.find('.st-featured-image');
		$featured.addClass('st-dip-white');
		$img.addClass('st-fade-out');
		setTimeout(function(){
			callback();
			setTimeout(function(){
				$featured.removeClass('st-dip-white');
				$img.removeClass('st-fade-out');
			},50);
		},150);
	}

	function updateGallery(images){
		var $gallery=$('.st-product-gallery');
		if(!images||!images.length)return;
		dipToWhite($gallery,function(){
			$gallery.find('.st-featured-image').attr('src',images[0].full).attr('alt',images[0].alt||'');
			if(images.length>1){
				var thumbs=images.map(function(img,i){
					return '<div class="st-thumb'+(i===0?' active':'')+'" data-full="'+img.full+'"><img src="'+img.thumb+'" alt="'+(img.alt||'')+'"/></div>';
				}).join('');
				$gallery.find('.st-gallery-thumbnails').html(thumbs).show();
			}else{
				$gallery.find('.st-gallery-thumbnails').hide();
			}
		});
	}

	$(document).on('click','.st-thumb',function(){
		var $t=$(this),$gallery=$t.closest('.st-product-gallery'),src=$t.data('full');
		if($t.hasClass('active'))return;
		$t.addClass('active').siblings().removeClass('active');
		dipToWhite($gallery,function(){
			$gallery.find('.st-featured-image').attr('src',src);
		});
	});

	$(document).on('found_variation','.variations_form',function(e,v){
		var $gallery=$('.st-product-gallery'),pid=$gallery.data('product');
		$.post(stVariationgallery.ajaxurl,{
			action:'st_get_variation_gallery',
			nonce:stVariationgallery.nonce,
			variation_id:v.variation_id,
			product_id:pid
		},function(r){
			if(r.success)updateGallery(r.data);
		});
	});

	$(document).on('reset_data','.variations_form',function(){
		var $gallery=$('.st-product-gallery'),pid=$gallery.data('product');
		$.post(stVariationgallery.ajaxurl,{
			action:'st_get_variation_gallery',
			nonce:stVariationgallery.nonce,
			variation_id:0,
			product_id:pid
		},function(r){
			if(r.success)updateGallery(r.data);
		});
	});

	// Handle preselected variation on page load.
	$(function(){
		var $form=$('.variations_form');
		if($form.length){
			setTimeout(function(){
				var vid=$form.find('input[name="variation_id"]').val();
				if(vid&&vid!='0'){
					var $gallery=$('.st-product-gallery'),pid=$gallery.data('product');
					$.post(stVariationgallery.ajaxurl,{
						action:'st_get_variation_gallery',
						nonce:stVariationgallery.nonce,
						variation_id:vid,
						product_id:pid
					},function(r){
						if(r.success)updateGallery(r.data);
					});
				}
			},500);
		}
	});
})(jQuery);
