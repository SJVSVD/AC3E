import{o as l,h as c,i as d,a as b,b as s,j as B,f as $,T as v,r as _,t as o}from"./index-a6b313c0.js";import{_ as u}from"./_plugin-vue_export-helper-c27b6911.js";const k={name:"reusableModal",data:()=>({isVisible:!1}),methods:{open(){this.isVisible=!0},close(){this.isVisible=!1}}},p={key:0,class:"reusable-modal"},P={class:"window"};function g(e,t,a,m,f,n){return l(),c(v,{name:"fade"},{default:d(()=>[e.isVisible?(l(),b("div",p,[s("div",P,[B(e.$slots,"default")])])):$("",!0)]),_:3})}const h=u(k,[["render",g]]),w={name:"ConfirmDialogue",components:{reusableModal:h},data:()=>({title:void 0,message:void 0,okButton:void 0,cancelButton:void 0,resolvePromise:void 0,rejectPromise:void 0}),methods:{show(e={}){return this.title=e.title,this.message=e.message,this.okButton=e.okButton,e.cancelButton&&(this.cancelButton=e.cancelButton),this.$refs.reusable.open(),new Promise((t,a)=>{this.resolvePromise=t,this.rejectPromise=a})},_confirm(){this.$refs.reusable.close(),this.resolvePromise(!0)},_cancel(){this.$refs.reusable.close(),this.resolvePromise(!1)}}},C={style:{"margin-top":"0"}},V={class:"btns"},j={class:"row"},y={class:"col-4"},D=s("div",{class:"col-4"},null,-1),M={class:"col-4"};function N(e,t,a,m,f,n){const r=_("reusable-modal");return l(),c(r,{ref:"reusable"},{default:d(()=>[s("h2",C,o(e.title),1),s("p",null,o(e.message),1),s("div",V,[s("div",j,[s("div",y,[s("a",{class:"btn btn-alert btn-sm",onClick:t[0]||(t[0]=(...i)=>n._cancel&&n._cancel(...i))},o(e.cancelButton),1)]),D,s("div",M,[s("a",{class:"btn btn-success btn-sm",onClick:t[1]||(t[1]=(...i)=>n._confirm&&n._confirm(...i))},o(e.okButton),1)])])])]),_:1},512)}const F=u(w,[["render",N]]),S={name:"ConfirmDialogue",components:{reusableModal:h},data:()=>({title:void 0,message:void 0,okButton:void 0,cancelButton:void 0,resolvePromise:void 0,rejectPromise:void 0}),methods:{show(e={}){return this.title=e.title,this.message=e.message,this.okButton=e.okButton,e.cancelButton&&(this.cancelButton=e.cancelButton),this.$refs.reusable.open(),new Promise((t,a)=>{this.resolvePromise=t,this.rejectPromise=a})},_confirm(){this.$refs.reusable.close(),this.resolvePromise(!0)},_cancel(){this.$refs.reusable.close(),this.resolvePromise(!1)}}},T={class:"mt-0"},E={class:"btns"};function q(e,t,a,m,f,n){const r=_("reusable-modal");return l(),c(r,{ref:"reusable"},{default:d(()=>[s("h2",T,o(e.title),1),s("p",null,o(e.message),1),s("div",E,[s("span",{class:"btn-ok",onClick:t[0]||(t[0]=(...i)=>n._confirm&&n._confirm(...i))},o(e.okButton),1)])]),_:1},512)}const G=u(S,[["render",q]]);export{G as a,F as m};