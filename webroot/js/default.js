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

	/**
	 * Atualiza o comboPesquisa com a resposta ajax
	 * resposta:
	 * item1,valor1;
	 * item2,valor2;
	 * item3,valor3;
	 */
	function setPesquisa(url,code)
	{
		var jId		= "#rePesquisa";
		var texto 	= $("#edPesquisa").val();
		var tam		= texto.length;
		var jUrl	= url+$("#slPesquisa").val()+'/'+encodeURIComponent(texto);

		if (code==27 || !texto)
		{
			$(jId).fadeOut("4000");
		} else
		{
			if(tam>2)
			{
				$(jId).load(jUrl, function(resposta, status, xhr)
				{
					if (status=='success')
					{
						$(jId).fadeIn();
						$(jId).html(resposta);
					}
				});
			} else
			{
				$(jId).fadeIn();
				$(jId).html('digite no mínimo 3 caracteres !!!');
			}
		}
	}
	
