const pName = document.getElementById('pName');
const pBrand = document.getElementById('pBrand');
const pMaterial = document.getElementById('pMaterial');
const pPrice = document.getElementById('pPrice');
const pDes = document.getElementById('pDes');

const qLeft = document.getElementById('qLeft');
const addColor = document.getElementById('addColor');
const addColorBtn = document.getElementById('addColorBtn');

const submitbtn = document.getElementById('submitbtn');
const cancelbtn = document.getElementById('cancelbtn');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

addColorBtn.addEventListener('click', (e) => {

	var table = document.createElement('table');
	table.setAttribute('class', 'table table-bordered addItem');

		var tr1 = document.createElement('tr');

			var th1 = document.createElement('th');

				var div1 = document.createElement('div');

					var input1_1 = document.createElement('input');
					input1_1.setAttribute('type', 'file');
					input1_1.setAttribute('class', 'imgInput');
					input1_1.setAttribute('multiple', true);
					input1_1.addEventListener('change', (e) => {
						if(e.target.files.length > 6){
							alert('You can\'t upload more than 6 images!');
							return;
						}
						var formData = new FormData();
						for(var j = 0; j < e.target.files.length; j++){
							formData.append(j, e.target.files[j]);
						}
						formData.append('old', e.target.closest('div').querySelector('.imgText').value);
						$.ajax({
							type: 'POST',
							url: '/addPimg',
							data: formData,
							success: function(response){
								console.log(response.message);
								e.target.closest('div').querySelector('.imgText').value = response.imgs;
								var imgcon = e.target.closest('table').querySelector('.imgshow');
								imgcon.replaceChildren();
								for(var j = 0; j < response.name.length; j++){
									var img = document.createElement('img');
									img.setAttribute('src', '/img/product/temp/' + response.name[j]);
									imgcon.appendChild(img);
								}
							},
							error: function(response){
								alert('An error orcured when uploading image(s)!');
							},
							cache: false,
							contentType: false,
							processData: false,
						})
					})

					var input1_2 = document.createElement('input');
					input1_2.setAttribute('type', 'hidden');
					input1_2.setAttribute('class', 'imgText');

					var span1 = document.createElement('span');
					span1.innerHTML = 'Images (max 6 images)';

					var button1 = document.createElement('button');
					button1.setAttribute('type', 'button');
					button1.setAttribute('class', 'btn btn-block btn-warning addImg');
					button1.innerHTML = 'Select';
					button1.addEventListener('click', (e) => {
						if(!confirm('All previous uploaded images will be removed.\nAre you sure want to continue?')){
							return;
						}
						e.target.closest('div').querySelector('.imgInput').click();
					})

				div1.appendChild(input1_1);
				div1.appendChild(input1_2);
				div1.appendChild(span1);
				div1.appendChild(button1);
			
			th1.appendChild(div1);

			var th2 = document.createElement('th');

				var div2 = document.createElement('div');

					var span2 = document.createElement('span');
					span2.innerHTML = 'Color';

					var button2 = document.createElement('button');
					button2.setAttribute('type', 'button');
					button2.setAttribute('class', 'btn btn-block btn-danger removeColor');
					button2.innerHTML = 'Remove';
					button2.addEventListener('click', (e) => {
						var imgs = e.target.closest('table').querySelector('.imgText').value;
						var formData = new FormData();
						formData.append('imgs', imgs);
						$.ajax({
							type: 'POST',
							url: '/removePcolor',
							data: formData,
							success: function(response){
								console.log(response.message);
							},
							error: function(response){
								console.log('An error orcured when removing images of color!');
							},
							cache: false,
							contentType: false,
							processData: false,
						})
						e.target.closest('table').remove();
					})

				div2.appendChild(span2);
				div2.appendChild(button2);

			th2.appendChild(div2);

			var th3 = document.createElement('th');

				var div3 = document.createElement('div');

					var span3 = document.createElement('span');
					span3.innerHTML = 'Size';

					var button3 = document.createElement('button');
					button3.setAttribute('type', 'button');
					button3.setAttribute('class', 'btn btn-block btn-secondary addSize');
					button3.innerHTML = 'Add';
					button3.addEventListener('click', (e) => {
						$newcs = Number(e.target.closest('table').querySelector('.imgcol').getAttribute('rowspan')) + 1;
						e.target.closest('table').querySelector('.imgcol').setAttribute('rowspan', $newcs);
						e.target.closest('table').querySelector('.colorcol').setAttribute('rowspan', $newcs);
						var tr = document.createElement('tr');
							var td1 = document.createElement('td');
								var input1 = document.createElement('input');
								input1.setAttribute('type', 'number');
								input1.setAttribute('min', 0);
								input1.setAttribute('step', 0.25);
								input1.setAttribute('class', 'pSize');
								input1.setAttribute('value', 0);
								input1.addEventListener('change', (e) => {
									if(e.target.value == ''){
										e.target.value = 0;
										return;
									}
									var size = e.target.closest('table').querySelectorAll('.pSize');
									var count = 0;
									for(var j = 0; j < size.length; j++){
										if(e.target.value == size[j].value){
											count += 1;
										}
										if(e.target.value == size[j].value && count == 2){
											alert('Size '+e.target.value+' \'s already existing!')
											e.target.value = '';
											e.target.focus();
											return;
										}
									}
								})
							td1.appendChild(input1);
							var td2 = document.createElement('td');
								var input2 = document.createElement('input');
								input2.setAttribute('type', 'number');
								input2.setAttribute('min', 0);
								input2.setAttribute('class', 'pQuan');
								input2.setAttribute('value', 0);
								input2.addEventListener('change', (e) => {
									if(e.target.value == ''){
										e.target.value = 0;
									}
									else{
										e.target.value = Number.parseInt(e.target.value);
									}
								})
							td2.appendChild(input2);
							var td3 = document.createElement('td');
							td3.setAttribute('class', 'removecol');
								var button3 = document.createElement('button');
								button3.setAttribute('type', 'button');
								button3.setAttribute('class', 'btn btn-block btn-danger removeSize');
								button3.innerHTML = 'X';
								button3.addEventListener('click', (e) => {
									$newcs = Number(e.target.closest('table').querySelector('.imgcol').getAttribute('rowspan')) - 1;
									e.target.closest('table').querySelector('.imgcol').setAttribute('rowspan', $newcs);
									e.target.closest('table').querySelector('.colorcol').setAttribute('rowspan', $newcs);
									e.target.closest('tr').remove();
								})
							td3.appendChild(button3);
						tr.appendChild(td1);
						tr.appendChild(td2);
						tr.appendChild(td3);
						e.target.closest('table').appendChild(tr);
					})

				div3.appendChild(span3);
				div3.appendChild(button3);

			th3.appendChild(div3);

			var th4 = document.createElement('th');
			th4.innerHTML = 'Quantity';

			var th5 = document.createElement('th');

		tr1.appendChild(th1);
		tr1.appendChild(th2);
		tr1.appendChild(th3);
		tr1.appendChild(th4);
		tr1.appendChild(th5);

		var tr2 = document.createElement('tr')

			var td1 = document.createElement('td');
			td1.setAttribute('class', 'imgcol');
			td1.setAttribute('rowspan', 2);

				var div4 = document.createElement('div');
				div4.setAttribute('class', 'imgshow');

			td1.appendChild(div4)

			var td2 = document.createElement('td');
			td2.setAttribute('class', 'colorcol');
			td2.setAttribute('rowspan', 2);

				var textarea = document.createElement('textarea');
				textarea.setAttribute('class', 'pColor');
				textarea.addEventListener('change', (e) => {
					var color = document.getElementsByClassName('pColor');
					var count = 0;
					for(var j = 0; j < color.length; j++){
						if(e.target.value == color[j].value){
							count += 1;
						}
						if(e.target.value == color[j].value && count > 1){
							alert('Color '+e.target.value+' \'s already existing!');
							e.target.value = '';
							e.target.focus();
							return;
						}
					}
				})

			td2.appendChild(textarea);

		tr2.appendChild(td1);
		tr2.appendChild(td2);

		var tr3 = document.createElement('tr');

			var td3 = document.createElement('td');
			
				var input3 = document.createElement('input');
				input3.setAttribute('type', 'number');
				input3.setAttribute('min', 0);
				input3.setAttribute('step', 0.25);
				input3.setAttribute('class', 'pSize');
				input3.setAttribute('value', 0);
				input3.addEventListener('change', (e) => {
					if(e.target.value == ''){
						e.target.value = 0;
						return;
					}
					var size = e.target.closest('table').querySelectorAll('.pSize');
					var count = 0;
					for(var j = 0; j < size.length; j++){
						if(e.target.value == size[j].value){
							count += 1;
						}
						if(e.target.value == size[j].value && count == 2){
							alert('Size '+e.target.value+' \'s already existing!')
							e.target.value = '';
							e.target.focus();
							return;
						}
					}
				})

			td3.appendChild(input3);

			var td4 = document.createElement('td');

				var input4 = document.createElement('input');
				input4.setAttribute('type', 'number');
				input4.setAttribute('min', 0);
				input4.setAttribute('class', 'pQuan');
				input4.setAttribute('value', 0);
				input4.addEventListener('change', (e) => {
					if(e.target.value == ''){
						e.target.value = 0;
					}
					else{
						e.target.value = Number.parseInt(e.target.value);
					}
				})

			td4.appendChild(input4);

			var td5 = document.createElement('td');
			td5.setAttribute('class', 'removecol');

				var button5 = document.createElement('button');
				button5.setAttribute('type', 'button');
				button5.setAttribute('class', 'btn btn-block btn-danger removeSize');
				button5.innerHTML = 'X';
				button5.addEventListener('click', (e) => {
					$newcs = Number(e.target.closest('table').querySelector('.imgcol').getAttribute('rowspan')) - 1;
					e.target.closest('table').querySelector('.imgcol').setAttribute('rowspan', $newcs);
					e.target.closest('table').querySelector('.colorcol').setAttribute('rowspan', $newcs);
					e.target.closest('tr').remove();
				})

			td5.appendChild(button5);

		tr3.appendChild(td3);
		tr3.appendChild(td4);
		tr3.appendChild(td5);

	table.appendChild(tr1);
	table.appendChild(tr2);
	table.appendChild(tr3);

	addColor.appendChild(table);

});

var addImg = document.getElementsByClassName('addImg');
for(var i = 0; i < addImg.length; i++){
	addImg[i].addEventListener('click', (e) => {
		if(!confirm('All previous uploaded images will be removed.\nAre you sure want to continue?')){
			return;
		}
		e.target.closest('div').querySelector('.imgInput').click();
	})
}

var imgInput = document.getElementsByClassName('imgInput');
for(var i = 0; i < imgInput.length; i++){
	imgInput[i].addEventListener('change', (e) => {
		if(e.target.files.length > 6){
			alert('You can\'t upload more than 6 images!');
			return;
		}
		var formData = new FormData();
		for(var j = 0; j < e.target.files.length; j++){
			formData.append(j, e.target.files[j]);
		}
		formData.append('old', e.target.closest('div').querySelector('.imgText').value);
		$.ajax({
			type: 'POST',
			url: '/addPimg',
			data: formData,
			success: function(response){
				console.log(response.message);
				e.target.closest('div').querySelector('.imgText').value = response.imgs;
				var imgcon = e.target.closest('tbody').querySelector('.imgshow');
				imgcon.replaceChildren();
				for(var j = 0; j < response.name.length; j++){
					var img = document.createElement('img');
					img.setAttribute('src', '/img/product/temp/' + response.name[j]);
					imgcon.appendChild(img);
				}
			},
			error: function(response){
				alert('An error orcured when uploading image(s)!');
			},
			cache: false,
			contentType: false,
			processData: false,
		})
	})
}

var addSize = document.getElementsByClassName('addSize');
for(var i = 0; i < addSize.length; i++){
	addSize[i].addEventListener('click', (e) => {
		$newcs = Number(e.target.closest('table').querySelector('.imgcol').getAttribute('rowspan')) + 1;
		e.target.closest('table').querySelector('.imgcol').setAttribute('rowspan', $newcs);
		e.target.closest('table').querySelector('.colorcol').setAttribute('rowspan', $newcs);
		var tr = document.createElement('tr');
			var td1 = document.createElement('td');
				var input1 = document.createElement('input');
				input1.setAttribute('type', 'number');
				input1.setAttribute('min', 0);
				input1.setAttribute('step', 0.25);
				input1.setAttribute('class', 'pSize');
				input1.setAttribute('value', 0);
				input1.addEventListener('change', (e) => {
					if(e.target.value == ''){
						e.target.value = 0;
						return;
					}
					var size = e.target.closest('table').querySelectorAll('.pSize');
					var count = 0;
					for(var j = 0; j < size.length; j++){
						if(e.target.value == size[j].value){
							count += 1;
						}
						if(e.target.value == size[j].value && count == 2){
							alert('Size '+e.target.value+' \'s already existing!')
							e.target.value = '';
							e.target.focus();
							return;
						}
					}
				})
			td1.appendChild(input1);
			var td2 = document.createElement('td');
				var input2 = document.createElement('input');
				input2.setAttribute('type', 'number');
				input2.setAttribute('min', 0);
				input2.setAttribute('class', 'pQuan');
				input2.setAttribute('value', 0);
				input2.addEventListener('change', (e) => {
					if(e.target.value == ''){
						e.target.value = 0;
					}
					else{
						e.target.value = Number.parseInt(e.target.value);
					}
				})
			td2.appendChild(input2);
			var td3 = document.createElement('td');
			td3.setAttribute('class', 'removecol');
				var button3 = document.createElement('button');
				button3.setAttribute('type', 'button');
				button3.setAttribute('class', 'btn btn-block btn-danger removeSize');
				button3.innerHTML = 'X';
				button3.addEventListener('click', (e) => {
					$newcs = Number(e.target.closest('table').querySelector('.imgcol').getAttribute('rowspan')) - 1;
					e.target.closest('table').querySelector('.imgcol').setAttribute('rowspan', $newcs);
					e.target.closest('table').querySelector('.colorcol').setAttribute('rowspan', $newcs);
					e.target.closest('tr').remove();
				})
			td3.appendChild(button3);
		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		e.target.closest('tbody').appendChild(tr);
	})
}

var removeSize = document.getElementsByClassName('removeSize');
for(var i = 0; i < removeSize.length; i++){
	removeSize[i].addEventListener('click', (e) => {
		$newcs = Number(e.target.closest('table').querySelector('.imgcol').getAttribute('rowspan')) - 1;
		e.target.closest('table').querySelector('.imgcol').setAttribute('rowspan', $newcs);
		e.target.closest('table').querySelector('.colorcol').setAttribute('rowspan', $newcs);
		e.target.closest('tr').remove();
	})
}

var pColor = document.getElementsByClassName('pColor');
for(var i = 0; i < pColor.length; i++){
	pColor[i].addEventListener('change', (e) => {
		var color = document.getElementsByClassName('pColor');
		var count = 0;
		for(var j = 0; j < color.length; j++){
			if(e.target.value == color[j].value){
				count += 1;
			}
			if(e.target.value == color[j].value && count > 1){
				alert('Color '+e.target.value+' \'s already existing!');
				e.target.value = '';
				e.target.focus();
				return;
			}
		}
	})
}

var pSize = document.getElementsByClassName('pSize');
for(var i = 0; i < pSize.length; i++){
	pSize[i].addEventListener('change', (e) => {
		if(e.target.value == ''){
			e.target.value = 0;
			return;
		}
		var size = e.target.closest('table').querySelectorAll('.pSize');
		var count = 0;
		for(var j = 0; j < size.length; j++){
			if(e.target.value == size[j].value){
				count += 1;
			}
			if(e.target.value == size[j].value && count == 2){
				alert('Size '+e.target.value+' \'s already existing!')
				e.target.value = '';
				e.target.focus();
				return;
			}
		}
	})
}

var pQuan = document.getElementsByClassName('pQuan');
for(var i = 0; i < pQuan.length; i++){
	pQuan[i].addEventListener('change', (e) => {
		if(e.target.value == ''){
			e.target.value = 0;
		}
		else{
			e.target.value = Number.parseInt(e.target.value);
		}
	})
}

var removeColor = document.getElementsByClassName('removeColor');
for(var i = 0; i < removeColor.length; i++){
	removeColor[i].addEventListener('click', (e) => {
		var imgs = e.target.closest('table').querySelector('.imgText').value;
		var formData = new FormData();
		formData.append('imgs', imgs);
		$.ajax({
			type: 'POST',
			url: '/removePcolor',
			data: formData,
			success: function(response){
				console.log(response.message);
			},
			error: function(response){
				console.log('An error orcured when removing images of color!');
			},
			cache: false,
			contentType: false,
			processData: false,
		})
		e.target.closest('table').remove();
	})
}

