import{m as d,a as _}from"./alertModal-064cb8ca.js";import{m}from"./mixins-00a35343.js";import{o,h as b,i as f,b as s,j as c,g as t,t as n,a as e,T as r}from"./index-a6b313c0.js";import{_ as u}from"./_plugin-vue_export-helper-c27b6911.js";const h={components:{modalconfirmacion:d,modalalerta:_},mixins:[m],data:()=>({}),props:{nonIsiPublication1:Object},created(){}},y={name:"modal"},P={class:"modal-mask"},x={class:"modal-wrapper"},w={class:"modal-container"},I={class:"modal-header pb-1",style:{"font-weight":"bold",color:"#444444"}},k=s("label",{class:"col-5 m-0",style:{"font-size":"17px"}},"Non ISI Publication Details: ",-1),z={class:"col-5 m-0 fs-8"},p=s("label",{class:"fw-normal",style:{"font-size":"14px"}},null,-1),g={class:"modal-body"},v={class:"form-group"},B={class:"row"},N=s("div",{class:"text-uppercase pb-4"},"General information:",-1),T={class:"col-md-4 m-0 fs-8 pb-1"},D={class:"fw-normal",style:{"font-size":"14px"}},j={class:"col-md-8 m-0 fs-8 pb-1"},S={key:0,class:"fw-normal",style:{"font-size":"14px"}},A={key:1,class:"fw-normal",style:{"font-size":"14px"}},C={class:"col-md-10 m-0 fs-8 pb-1"},V={key:0,class:"fw-normal",style:{"font-size":"14px"}},F={key:1,class:"fw-normal",style:{"font-size":"14px"}},R={class:"col-md-10 m-0 fs-8 pb-1"},Y={key:0,class:"fw-normal",style:{"font-size":"14px"}},E={key:1,class:"fw-normal",style:{"font-size":"14px"}},G={class:"col-md-3 m-0 fs-8 pb-1"},J={key:0,class:"fw-normal",style:{"font-size":"14px"}},L={key:1,class:"fw-normal",style:{"font-size":"14px"}},M={class:"col-md-3 m-0 fs-8 pb-1"},O={key:0,class:"fw-normal",style:{"font-size":"14px"}},U={key:1,class:"fw-normal",style:{"font-size":"14px"}},X={class:"col-md-3 m-0 fs-8 pb-1"},q={key:0,class:"fw-normal",style:{"font-size":"14px"}},H={key:1,class:"fw-normal",style:{"font-size":"14px"}},K={class:"col-md-3 m-0 fs-8 pb-1"},Q={key:0,class:"fw-normal",style:{"font-size":"14px"}},W={key:1,class:"fw-normal",style:{"font-size":"14px"}},Z={class:"col-md-3 m-0 fs-8 pb-1"},$={key:0,class:"fw-normal",style:{"font-size":"14px"}},ss={key:1,class:"fw-normal",style:{"font-size":"14px"}},ls={class:"col-md-9 m-0 fs-8 pb-1"},os={key:0,class:"fw-normal",style:{"font-size":"14px"}},es={key:1,class:"fw-normal",style:{"font-size":"14px"}},ts={class:"col-md-6 m-0 fs-8 pb-1"},ns={key:0,class:"fw-normal",style:{"font-size":"14px"}},as={key:1,class:"fw-normal",style:{"font-size":"14px"}},is={class:"col-md-6 m-0 fs-8 pb-1"},cs={key:0,class:"fw-normal",style:{"font-size":"14px"}},ds={key:1,class:"fw-normal",style:{"font-size":"14px"}},_s={class:"col-md-6 m-0 fs-8 pb-1"},ms={key:0,class:"fw-normal",style:{"font-size":"14px"}},bs={key:1,class:"fw-normal",style:{"font-size":"14px"}},fs={class:"col-md-6 m-0 fs-8 pb-1"},rs={class:"fw-normal",style:{"font-size":"14px"}};function us(a,i,l,hs,ys,Ps){return o(),b(r,{name:"modalver"},{default:f(()=>[s("div",y,[s("div",P,[s("div",x,[s("div",w,[s("div",I,[c(a.$slots,"header",{},()=>[k,s("label",z,[t(" User: "),p,t(n(l.nonIsiPublication1.usuario.name),1)])]),s("a",{class:"btn btn-closed",onClick:i[0]||(i[0]=xs=>a.$emit("close")),ref:"closeBtn"},"X",512)]),s("div",g,[c(a.$slots,"body",{},()=>[s("div",v,[s("div",B,[N,s("label",T,[t(" Status: "),s("label",D,n(l.nonIsiPublication1.status),1)]),s("label",j,[t(" Authors: "),l.nonIsiPublication1.authors!=null?(o(),e("label",S,n(l.nonIsiPublication1.authors),1)):(o(),e("label",A,"---"))]),s("label",C,[t(" Article Title: "),l.nonIsiPublication1.articleTitle!=null?(o(),e("label",V,n(l.nonIsiPublication1.articleTitle),1)):(o(),e("label",F,"---"))]),s("label",R,[t(" Journal Name: "),l.nonIsiPublication1.journalName!=null?(o(),e("label",Y,n(l.nonIsiPublication1.journalName),1)):(o(),e("label",E,"---"))]),s("label",G,[t(" Volume: "),l.nonIsiPublication1.volume!=null?(o(),e("label",J,n(l.nonIsiPublication1.volume),1)):(o(),e("label",L,"---"))]),s("label",M,[t(" First Page: "),l.nonIsiPublication1.firstPage!=null?(o(),e("label",O,n(l.nonIsiPublication1.firstPage),1)):(o(),e("label",U,"---"))]),s("label",X,[t(" Last Page: "),l.nonIsiPublication1.lastPage!=null?(o(),e("label",q,n(l.nonIsiPublication1.lastPage),1)):(o(),e("label",H,"---"))]),s("label",K,[t(" Year Published: "),l.nonIsiPublication1.yearPublished!=null?(o(),e("label",Q,n(l.nonIsiPublication1.yearPublished),1)):(o(),e("label",W,"---"))]),s("label",Z,[t(" Month: "),l.nonIsiPublication1.month!=null?(o(),e("label",$,n(l.nonIsiPublication1.month),1)):(o(),e("label",ss,"---"))]),s("label",ls,[t(" Funding: "),l.nonIsiPublication1.funding!=null?(o(),e("label",os,n(l.nonIsiPublication1.funding),1)):(o(),e("label",es,"---"))]),s("label",ts,[t(" Indexed by: "),l.nonIsiPublication1.indexedBy!=null?(o(),e("label",ns,n(l.nonIsiPublication1.indexedBy),1)):(o(),e("label",as,"---"))]),s("label",is,[t(" Acceptance Date: "),l.nonIsiPublication1.acceptanceDate!=null?(o(),e("label",cs,n(l.nonIsiPublication1.acceptanceDate),1)):(o(),e("label",ds,"---"))]),s("label",_s,[t(" Comments: "),l.nonIsiPublication1.comments!=null?(o(),e("label",ms,n(l.nonIsiPublication1.comments),1)):(o(),e("label",bs,"---"))]),s("label",fs,[t(" Progress Report Year: "),s("label",rs,n(l.nonIsiPublication1.progressReport),1)])])])])])])])])])]),_:3})}const ps=u(h,[["render",us]]);export{ps as m};