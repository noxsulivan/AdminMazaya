var Validator = Class.create();

Validator.prototype = {
	initialize : function(className, error, test, options) {
		if(typeof test == 'function'){
			this.options = $H(options);
			this._test = test;
		} else {
			this.options = $H(test);
			this._test = function(){return true};
		}
		this.error = error || 'Validation failed.';
		this.className = className;
	},
	test : function(v, elm) {
		return (this._test(v,elm) && this.options.all(function(p){
			return Validator.methods[p.key] ? Validator.methods[p.key](v,elm,p.value) : true;
		}));
	}
}
Validator.methods = {
	pattern : function(v,elm,opt) {return Validation.get('IsEmpty').test(v) || opt.test(v)},
	minLength : function(v,elm,opt) {return v.length >= opt},
	maxLength : function(v,elm,opt) {return v.length <= opt},
	min : function(v,elm,opt) {return v >= parseFloat(opt)}, 
	max : function(v,elm,opt) {return v <= parseFloat(opt)},
	notOneOf : function(v,elm,opt) {return $A(opt).all(function(value) {
		return v != value;
	})},
	oneOf : function(v,elm,opt) {return $A(opt).any(function(value) {
		return v == value;
	})},
	is : function(v,elm,opt) {return v == opt},
	isNot : function(v,elm,opt) {return v != opt},
	equalToField : function(v,elm,opt) {return v == $F(opt)},
	notEqualToField : function(v,elm,opt) {return v != $F(opt)},
	include : function(v,elm,opt) {return $A(opt).all(function(value) {
		return Validation.get(value).test(v,elm);
	})}
}

var Validation = Class.create();

Validation.prototype = {
	initialize : function(form, options){
		this.options = Object.extend({
			onSubmit : true,
			stopOnFirst : false,
			immediate : false,
			focusOnError : true,
			useTitles : false,
			onFormValidate : function(result, form) {},
			onElementValidate : function(result, elm) {}
		}, options || {});
		this.form = $(form);
		if(this.options.onSubmit) Event.observe(this.form,'submit',this.onSubmit.bind(this),false);
		if(this.options.immediate) {
			var useTitles = this.options.useTitles;
			var callback = this.options.onElementValidate;
			Form.getElements(this.form).each(function(input) { // Thanks Mike!
				Event.observe(input, 'blur', function(ev) { Validation.validate(Event.element(ev),{useTitle : useTitles, onElementValidate : callback}); });
			});
		}
	},
	onSubmit :  function(ev){
		if(!this.validate()) Event.stop(ev);
	},
	validate : function() {
		var result = false;
		var useTitles = this.options.useTitles;
		var callback = this.options.onElementValidate;
		if(this.options.stopOnFirst) {
			result = Form.getElements(this.form).all(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); });
		} else {
			result = Form.getElements(this.form).collect(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); }).all();
		}
		if(!result && this.options.focusOnError) {
			Form.getElements(this.form).findAll(function(elm){return $(elm).hasClassName('validation-failed')}).first().focus()
		}
		this.options.onFormValidate(result, this.form);
		return result;
	},
	reset : function() {
		Form.getElements(this.form).each(Validation.reset);
	}
}

Object.extend(Validation, {
	validate : function(elm, options){
		options = Object.extend({
			useTitle : false,
			onElementValidate : function(result, elm) {}
		}, options || {});
		elm = $(elm);
		var cn = elm.classNames();
		return result = cn.all(function(value) {
			var test = Validation.test(value,elm,options.useTitle);
			options.onElementValidate(test, elm);
			return test;
		});
	},
	test : function(name, elm, useTitle) {
		var v = Validation.get(name);
		var prop = '__advice'+name.camelize();
		try {
		if(!v.test($F(elm), elm)) {
		//if(Validation.isVisible(elm) && !v.test($F(elm), elm)) {
			if(!elm[prop]) {
				var advice = Validation.getAdvice(name, elm);
				if(advice == null) {
					var errorMsg = useTitle ? ((elm && elm.title) ? elm.title : v.error) : v.error;
					advice = '<div class="validation-advice" id="advice-' + name + '-' + Validation.getElmID(elm) +'" style="display:none">' + errorMsg + '</div>'
					switch (elm.type.toLowerCase()) {
						case 'checkbox':
						case 'radio':
							var p = elm.parentNode;
							if(p) {
								new Insertion.Bottom(p, advice);
							} else {
								new Insertion.After(elm, advice);
							}
							break;
						default:
							new Insertion.After(elm, advice);
				    }
					advice = Validation.getAdvice(name, elm);
				}
				if(typeof Effect == 'undefined') {
					advice.style.display = 'block';
				} else {
					new Effect.Appear(advice, {duration : 1 });
				}
			}
			elm[prop] = true;
			elm.removeClassName('validation-passed');
			elm.addClassName('validation-failed');
			return false;
		} else {
			var advice = Validation.getAdvice(name, elm);
			if(advice != null) advice.hide();
			elm[prop] = '';
			elm.removeClassName('validation-failed');
			elm.addClassName('validation-passed');
			return true;
		}
		} catch(e) {
			throw(e)
		}
	},
	isVisible : function(elm) {
		while(elm.tagName != 'BODY') {
			if(!$(elm).visible()) return false;
			elm = elm.parentNode;
		}
		return true;
	},
	getAdvice : function(name, elm) {
		return $('advice-' + name + '-' + Validation.getElmID(elm)) || $('advice-' + Validation.getElmID(elm));
	},
	getElmID : function(elm) {
		return elm.id ? elm.id : elm.name;
	},
	reset : function(elm) {
		elm = $(elm);
		var cn = elm.classNames();
		cn.each(function(value) {
			var prop = '__advice'+value.camelize();
			if(elm[prop]) {
				var advice = Validation.getAdvice(value, elm);
				advice.hide();
				elm[prop] = '';
			}
			elm.removeClassName('validation-failed');
			elm.removeClassName('validation-passed');
		});
	},
	add : function(className, error, test, options) {
		var nv = {};
		nv[className] = new Validator(className, error, test, options);
		Object.extend(Validation.methods, nv);
	},
	addAllThese : function(validators) {
		var nv = {};
		$A(validators).each(function(value) {
				nv[value[0]] = new Validator(value[0], value[1], value[2], (value.length > 3 ? value[3] : {}));
			});
		Object.extend(Validation.methods, nv);
	},
	get : function(name) {
		return  Validation.methods[name] ? Validation.methods[name] : Validation.methods['_LikeNoIDIEverSaw_'];
	},
	methods : {
		'_LikeNoIDIEverSaw_' : new Validator('_LikeNoIDIEverSaw_','',{})
	}
});

Validation.add('IsEmpty', '', function(v) {
				return  ((v == null) || (v.length == 0)); // || /^\s+$/.test(v));
			});

Validation.addAllThese([
	['required', '&raquo; Obrigatório.', function(v) {
				return !Validation.get('IsEmpty').test(v);
			}],
	['validate-minsize', 'Digite pelo menos 6 caracteres', function(v) {
				return Validation.get('IsEmpty').test(v) || v.length >= 6;
			}],
	['validate-number', 'Digite um número válido neste campo.', function(v) {
				return Validation.get('IsEmpty').test(v) || (!isNaN(v) && !/^\s+$/.test(v));
			}],
	['validate-digits', 'Digite apenas números neste campo. Evite espaços em branco e outros caracteres como pontos ou vírgulas.', function(v) {
				return Validation.get('IsEmpty').test(v) ||  !/[^\d]/.test(v);
			}],
	['validate-alpha', 'Digite apenas letras (a-z) neste campo.', function (v) {
				return Validation.get('IsEmpty').test(v) ||  /^[a-zA-Z]+$/.test(v)
			}],
	['validate-alphanum', 'Digite apenas letras (a-z) ou número (0-9) neste campo. Espaços em branco e outros caracteres não são permitidos.', function(v) {
				return Validation.get('IsEmpty').test(v) ||  !/\W/.test(v)
			}],
	['validate-email', 'Digite um endereço de e-mail válido. Por exemplo seunome@seusite.com.br .', function (v) {
				return Validation.get('IsEmpty').test(v) || /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(v)
			}],
	['validate-phone', 'Digite um telefone.', function (v) {
				return Validation.get('IsEmpty').test(v) || /[(]\d{2}[) ]\d{4}[-]\d{3,4}$/.test(v)
			}],
	['validate-url', 'Digite um endereço web válido.', function (v) {
				return Validation.get('IsEmpty').test(v) || /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(v)
			}],
	['validate-date', 'Digite uma data no formato: dd/mm/aaaa.', function(v) {
				return Validation.get('IsEmpty').test(v) || /^(\d{2})\/(\d{2})\/(\d{4})$/.test(v)
			}],
	['validate-currency-dollar', 'Digite um preço válido. por exemplo R$100.00 .', function(v) {
				return Validation.get('IsEmpty').test(v) ||  /^([1-9]{1}[0-9]{0,2}(\.[0-9]{3})*(\,[0-9]{0,2})?|[1-9]{1}\d*(\,[0-9]{0,2})?|0(\,[0-9]{0,2})?|(\,[0-9]{1,2})?)$/.test(v)
			}],
	['validate-selection', 'Selecione uma opção', function(v,elm){
				return elm.options ? elm.selectedIndex > 0 : !Validation.get('IsEmpty').test(v);
			}],
	['validate-one-required', 'Selecione uma das opções.', function (v,elm) {
				var p = elm.parentNode;
				var options = p.getElementsByTagName('INPUT');
				return $A(options).any(function(elm) {
					return $F(elm);
				});
			}],
	['validate-cpf', 'O CPF não é válido.', function (v,elm) {
				if(Validation.get('IsEmpty').test(v)) return true;
				var regex = /\d{3}[.]\d{3}[.]\d{3}[-]\d{2}$/;
				if(!regex.test(v)) return false;
				

				v = v.replace('.','').replace('.','').replace('-','')
				
				if(v.length != 11 || v == "00000000000" || v == "11111111111" ||
				  v == "22222222222" || v == "33333333333" || v == "44444444444" ||
				  v == "55555555555" || v == "66666666666" || v == "77777777777" ||
				  v == "88888888888" || v == "99999999999"){
				  return false;
			   }
			   
				soma = 0;
				for(i = 0; i < 9; i++)
					soma += parseInt(v.charAt(i)) * (10 - i);
				resto = 11 - (soma % 11);
				if(resto == 10 || resto == 11) resto = 0;
				if(resto != parseInt(v.charAt(9))) return false;
				
				soma = 0;
				for(i = 0; i < 10; i ++)
					soma += parseInt(v.charAt(i)) * (11 - i);
				resto = 11 - (soma % 11);
				if(resto == 10 || resto == 11) resto = 0;
				if(resto != parseInt(v.charAt(10))) return false;
				
				return true;

			}]
]);