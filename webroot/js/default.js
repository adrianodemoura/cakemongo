	/**
	 * Exibe o menu da lista
	 * 
	 * parametros	id do menu a ser aberto
	 */
	function showMenu(id) 
	{
		$(".divMenuLista").fadeOut();
		showCampos('sombra');
		$("#menuLista"+id).fadeIn();
		$("#menuLista"+id).mouseleave(function() { $(this).fadeOut(); showCampos('none'); });
	}

	function showCampos(c)
	{
		$(".campos .editarCmp label").attr('class',c);
		$(".campos .editarCmp input").attr('class',c);
		$(".campos .editarCmp select").attr('class',c);
		$(".campos .editarCmp textarea").attr('class',c);
	}

	/**
	 * 
	 */
	function setBusca(jId,jCo,code)
	{
		var texto 	= $("#"+jId).val();
		var jRe		= "#re"+jId;
		var jUrl	= url+'/'+jCo+'/busca_ajax/'+encodeURIComponent(texto);
		var tam		= texto.length;

		if (code==27 || !texto)
		{
			$(jRe).fadeOut("4000");
		} else if(code>40)
		{
			if(tam>0)
			{
				$(jRe).load(jUrl, function(resposta, status, xhr)
				{
					if (status=='success')
					{
						$(jRe).fadeIn();
						$(jRe).html(resposta);
					}
				});
			} else
			{
				$(jRe).fadeIn();
				$(jRe).html('digite no mínimo 1 caracter !!!');
			}
		}
	}
	
	
