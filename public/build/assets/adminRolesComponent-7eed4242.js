import{m as C,a as p}from"./mixins-00a35343.js";import{m as R,a as y}from"./alertModal-064cb8ca.js";import{r as c,K as G,o as t,h as w,i as T,b as e,j as b,g as f,w as k,k as O,a as m,t as u,f as $,F as E,e as x,m as V,n as _,T as H,v as D,p as M}from"./index-a6b313c0.js";import{_ as P}from"./_plugin-vue_export-helper-c27b6911.js";import"./main-a60f64cc.js";const F={components:{modalconfirmacion:R,modalalerta:y},mixins:[C],data:()=>({buttonText:"Guardar Rol",buttonDisable:!1,name:"",errors:[],permisos:null,permissions:[]}),created(){this.getPermisos()},methods:{cerrarModal(){const s=this.$refs.closeBtn;this.$emit("recarga"),s.click()},getPermisos(){p.get("api/permisos").then(s=>{this.permisos=s.data}).catch(s=>console.log(s))},async crearRol(){if(await this.$refs.confirmation.show({title:"Crear Rol",message:`¿Está seguro/a que desea crear el rol '${this.name}'? Esta acción no puede ser desecha.`,okButton:"Crear",cancelButton:"Volver"})){let o={name:this.name,permission:this.permissions};p.post("api/roles",o).then(n=>{this.buttonDisable=!0,this.buttonText="Guardando...",this.toast.success("Rol guardado con éxito!",{position:"top-right",timeout:3e3,closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:.6,showCloseButtonOnHover:!1,hideProgressBar:!0,closeButton:"button",icon:!0,rtl:!1}),setTimeout(()=>{this.cerrarModal()},1500)}).catch(n=>{n.response.status==422&&(this.errors=n.response.data.errors,this.toast.warning("Existe un valor inválido.",{position:"top-right",timeout:3e3,closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:.6,showCloseButtonOnHover:!1,hideProgressBar:!0,closeButton:"button",icon:!0,rtl:!1}))})}}}},L={name:"modal"},j={class:"modal-mask"},S={class:"modal-wrapper"},U={class:"modal-container-xs"},q={class:"modal-header fw-bold",style:{color:"#444444"}},X={class:"modal-body"},I={class:"form-group"},A=e("label",{for:"name"},"Nombre del Rol: ",-1),K=e("br",null,null,-1),z={key:0,class:"error"},J=e("label",{for:""},"Permisos para este Rol: ",-1),Q=e("br",null,null,-1),W={class:"form-check-label"},Y=["value"],Z={key:0,class:"error"},ee=e("br",null,null,-1),se={class:"modal-footer"},oe=["disabled"];function te(s,o,n,h,a,i){const v=c("modalconfirmacion"),g=c("modalalerta"),r=G("has-error");return t(),w(H,{name:"modalcrear"},{default:T(()=>[e("div",L,[e("div",j,[e("div",S,[e("div",U,[e("div",q,[b(s.$slots,"header",{},()=>[f(" Nuevo Rol ")]),e("a",{class:"btn btn-closed",onClick:o[0]||(o[0]=d=>s.$emit("close")),ref:"closeBtn"},"X",512)]),e("div",X,[b(s.$slots,"body",{},()=>[e("div",I,[A,K,k(e("input",{type:"text",class:"form-control","onUpdate:modelValue":o[1]||(o[1]=d=>s.name=d)},null,512),[[r,s.errors.name],[O,s.name]]),s.errors&&s.errors.name?(t(),m("div",z,u(s.errors.name[0]),1)):$("",!0)]),J,Q,(t(!0),m(E,null,x(s.permisos,d=>(t(),m("div",{key:d.id,class:"form-check"},[e("label",W,[k(e("input",{type:"checkbox",class:"form-check-input",value:d.id,"onUpdate:modelValue":o[2]||(o[2]=B=>s.permissions=B)},null,8,Y),[[V,s.permissions]]),f("  "+u(d.name),1)])]))),128)),s.errors&&s.errors.permission?(t(),m("div",Z,u(s.errors.permission[0]),1)):$("",!0),ee,e("div",se,[b(s.$slots,"footer",{},()=>[e("button",{class:"btn btn-continue",onClick:o[3]||(o[3]=d=>i.crearRol()),disabled:s.buttonDisable},u(s.buttonText),9,oe)])])])]),_(v,{ref:"confirmation"},null,512),_(g,{ref:"alert"},null,512)])])])])]),_:3})}const re=P(F,[["render",te]]),ae={components:{modalconfirmacion:R,modalalerta:y},mixins:[C],data:()=>({buttonText:"Editar Rol",buttonDisable:!1,name:"",id:"",errors:[],permisos:[],permissions:[],permisosRol:[],revision:!1}),props:{rol:Object},created(){this.name=this.rol.name,this.id=this.rol.id,this.getPermisos(),this.getPermisosRol(this.id)},methods:{cerrarModal(){const s=this.$refs.closeBtn;this.$emit("recarga"),s.click()},compararPermisos(){this.revision==!1&&this.permisos.forEach(s=>{this.permisosRol.forEach(o=>{s.name==o&&this.permissions.push(s.id)})}),this.revision=!0},getPermisosRol(s){p.get(`api/getPermisosRol/${s}`).then(o=>{this.permisosRol=o.data,this.compararPermisos()}).catch(o=>console.log(o))},getPermisos(){p.get("api/permisos").then(s=>{this.permisos=s.data}).catch(s=>console.log(s))},async editarRol(){if(await this.$refs.confirmation.show({title:"Editar Rol",message:`¿Está seguro/a que desea editar al rol '${this.rol.name}'? Esta acción no puede ser desecha.`,okButton:"Editar",cancelButton:"Volver"})){let n={name:this.name,permission:this.permissions};var o=this.id;p.put(`api/roles/${o}`,n).then(h=>{this.buttonText="Guardando...",this.buttonDisable=!0,this.toast.success("Rol editado con éxito!",{position:"top-right",timeout:3e3,closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:.6,showCloseButtonOnHover:!1,hideProgressBar:!0,closeButton:"button",icon:!0,rtl:!1}),setTimeout(()=>{this.cerrarModal()},1500)}).catch(h=>{h.response.status==422&&(this.errors=h.response.data.errors,this.toast.warning("Existe un valor inválido.",{position:"top-right",closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:.6,showCloseButtonOnHover:!1,hideProgressBar:!0,closeButton:"button",icon:!0,rtl:!1}))})}}}},le={name:"modal"},ne={class:"modal-mask"},ie={class:"modal-wrapper"},de={class:"modal-container-xs"},ce={class:"modal-header fw-bold",style:{color:"#444444"}},me={class:"modal-body"},ue={class:"form-group"},he=e("label",{for:"name"},"Nombre: ",-1),pe=e("br",null,null,-1),be={key:0,class:"error"},fe=e("label",{for:""},"Permisos para este Rol: ",-1),_e=e("br",null,null,-1),ve={class:"form-check-label"},ge=["id","value"],$e=e("br",null,null,-1),ke={class:"modal-footer"},we=["disabled"];function Ce(s,o,n,h,a,i){const v=c("modalconfirmacion"),g=c("modalalerta");return t(),w(H,{name:"modaleditar"},{default:T(()=>[e("div",le,[e("div",ne,[e("div",ie,[e("div",de,[e("div",ce,[b(s.$slots,"header",{},()=>[f(" Editar Rol ")]),e("a",{class:"btn btn-closed",onClick:o[0]||(o[0]=r=>s.$emit("close")),ref:"closeBtn"},"X",512)]),e("div",me,[b(s.$slots,"body",{},()=>[e("div",ue,[he,pe,k(e("input",{type:"text",class:"form-control","onUpdate:modelValue":o[1]||(o[1]=r=>s.name=r)},null,512),[[O,s.name]]),s.errors&&s.errors.name?(t(),m("div",be,u(s.errors.name[0]),1)):$("",!0)]),fe,_e,(t(!0),m(E,null,x(s.permisos,r=>(t(),m("div",{key:r.id,class:"form-check"},[e("label",ve,[k(e("input",{type:"checkbox",class:"form-check-input",id:r.id,value:r.id,"onUpdate:modelValue":o[2]||(o[2]=d=>s.permissions=d)},null,8,ge),[[V,s.permissions]]),f("  "+u(r.name),1)])]))),128)),$e,e("div",ke,[b(s.$slots,"footer",{},()=>[e("a",{class:"btn btn-continue",onClick:o[3]||(o[3]=r=>i.editarRol()),disabled:s.buttonDisable},u(s.buttonText),9,we)])])])])])]),_(v,{ref:"confirmation"},null,512),_(g,{ref:"alert"},null,512)])])]),_:3})}const Re=P(ae,[["render",Ce]]),ye={components:{modalconfirmacion:R,modalalerta:y},mixins:[C],data:()=>({buttonText:"Guardar Permiso",buttonDisable:!1,nombrePermiso:"",errors:[]}),created(){this.getPermisos()},methods:{cerrarModal(){const s=this.$refs.closeBtn;this.$emit("recarga"),s.click()},getPermisos(){p.get("api/permisos").then(s=>{this.permisos=s.data}).catch(s=>console.log(s))},async crearRol(){if(await this.$refs.confirmation.show({title:"Crear Permiso",message:`¿Está seguro/a que desea crear el permiso '${this.nombrePermiso}'? Esta acción no puede ser desecha.`,okButton:"Crear",cancelButton:"Volver"})){let o={name:this.nombrePermiso};p.post("api/permisos",o).then(n=>{this.buttonDisable=!0,this.buttonText="Guardando...",this.toast.success("Permiso guardado con éxito!",{position:"top-right",closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:.6,showCloseButtonOnHover:!1,hideProgressBar:!0,closeButton:"button",icon:!0,rtl:!1}),setTimeout(()=>{this.cerrarModal()},1500)}).catch(n=>{n.response.status==422&&(this.errors=n.response.data.errors,this.toast.warning("Existe un valor inválido.",{position:"top-right",timeout:3e3,closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:.6,showCloseButtonOnHover:!1,hideProgressBar:!0,closeButton:"button",icon:!0,rtl:!1}))})}}}},Pe={name:"modal"},Be={class:"modal-mask"},Te={class:"modal-wrapper"},Oe={class:"modal-container-xs"},Ee={class:"modal-header fw-bold",style:{color:"#444444"}},xe={class:"modal-body"},He={class:"form-group"},De=e("label",{for:"name"},"Nombre del permiso: ",-1),Ve=e("br",null,null,-1),Ne={key:0,class:"error"},Ge={class:"modal-footer"},Me=["disabled"];function Fe(s,o,n,h,a,i){const v=c("modalconfirmacion"),g=c("modalalerta");return t(),w(H,{name:"modalcrear"},{default:T(()=>[e("div",Pe,[e("div",Be,[e("div",Te,[e("div",Oe,[e("div",Ee,[b(s.$slots,"header",{},()=>[f(" Nuevo Permiso ")]),e("a",{class:"btn btn-closed",onClick:o[0]||(o[0]=r=>s.$emit("close")),ref:"closeBtn"},"X",512)]),e("div",xe,[b(s.$slots,"body",{},()=>[e("div",He,[De,Ve,k(e("input",{type:"text",class:"form-control","onUpdate:modelValue":o[1]||(o[1]=r=>s.nombrePermiso=r)},null,512),[[O,s.nombrePermiso]])]),s.errors&&s.errors.name?(t(),m("div",Ne,u(s.errors.name[0]),1)):$("",!0)])]),e("div",Ge,[b(s.$slots,"footer",{},()=>[e("button",{class:"btn btn-continue",onClick:o[2]||(o[2]=r=>i.crearRol()),disabled:s.buttonDisable},u(s.buttonText),9,Me)])]),_(v,{ref:"confirmation"},null,512),_(g,{ref:"alert"},null,512)])])])])]),_:3})}const Le=P(ye,[["render",Fe]]),je={components:{modalcrear2:re,modaleditar2:Re,modalconfirmacion:R,modalalerta:y,modalpermisos:Le},mixins:[C],data(){return{roles:null,showCrearRol:!1,showEditarRol:!1,showCrearPermiso:!1,roleditar:null,mostrarTabla:!1,mostrarCarga:!0,permisos:null,table:null}},created(){this.getRoles()},methods:{recargarTabla(s){this.mostrarCarga=!0,s=="General"?(this.roles=null,this.getRoles()):(this.table.destroy(),this.crearTabla("#MyTableRoles"))},getRoles(){p.get("api/roles").then(s=>{this.roles=s.data,this.table!=null&&(this.table.clear(),this.table.destroy()),this.crearTabla("#MyTableRoles")}).catch(s=>console.log(s))},rolEditar(s){this.roleditar=s,this.showEditarRol=!0},async deleteRole(s,o){await this.$refs.confirmation.show({title:"Borrar Rol",message:`¿Está seguro/a que desea eliminar al rol '${o}'? Esta acción no puede ser desecha.`,okButton:"Eliminar",cancelButton:"Volver"})&&p.delete(`api/roles/${s}`).then(h=>{this.toast.success("Rol eliminado con éxito!",{position:"top-right",timeout:3e3,closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:.6,showCloseButtonOnHover:!1,hideProgressBar:!0,closeButton:"button",icon:!0,rtl:!1}),this.recargarTabla("General")}).catch(h=>console.log(h))}}},Se={class:"row mt-4 mx-4"},Ue={class:"col-12"},qe={class:"card mb-4"},Xe={class:"row pb-0 p-4"},Ie={class:"col-12"},Ae={class:"d-flex justify-content-end"},Ke=e("i",{class:"fa-solid fa-rotate"},null,-1),ze=[Ke],Je={class:"card-body px-0 pt-0 pb-2",style:{"min-height":"400px"}},Qe={class:"container"},We={class:"table-responsive p-0"},Ye={class:"loader-sm"},Ze={class:"table align-items-center mb-0",id:"MyTableRoles"},es=e("thead",null,[e("tr",{style:{color:"black"}},[e("th",{style:{"min-width":"16px"}}),e("th",{class:"text-uppercase text-xs font-weight-bolder"},"ID-Rol"),e("th",{class:"text-uppercase text-xs font-weight-bolder"},"Nombre"),e("th",{class:"text-uppercase text-xs font-weight-bolder"},"Acciones")])],-1),ss=e("td",null,null,-1),os={class:"text-sm font-weight-bolder mb-0",style:{color:"black"}},ts={class:"text-sm mb-0"},rs={class:"align-middle text-end"},as={class:"d-flex px-3 py-1 justify-content-center align-items-center"},ls=["onClick"],ns=e("i",{class:"fa fa-fw fa-edit"},null,-1),is=[ns],ds=["onClick"],cs=e("i",{class:"fa fa-fw fa-trash"},null,-1),ms=[cs],us=M('<div class="row"><div class="col-3"><label style="font-weight:500;"> These buttons use the elements selected in the table, if none exist, it will select all the records. </label></div><div class="col-auto"><label title="To select a single record from the table, just do \r Click on the box in the first column to select \r several consecutive hold SHIFT, to select several \r non-consecutive hold CTRL."><span class="badge bg-dark-grey fs-10">?</span></label></div></div>',1),hs=e("div",{class:"row"},[e("div",{class:"col-4 align-left text-center float-end"}),e("div",{class:"col-4 align-left text-center float-end"}),e("div",{class:"col-4 align-left text-center float-end"})],-1);function ps(s,o,n,h,a,i){const v=c("modalconfirmacion"),g=c("modalalerta"),r=c("modaleditar2"),d=c("modalcrear2"),B=c("modalpermisos");return t(),m("div",Se,[e("div",Ue,[e("div",qe,[e("div",Xe,[e("div",Ie,[e("div",Ae,[e("a",{class:"btn btn-continue",id:"show-modal1",onClick:o[0]||(o[0]=l=>a.showCrearRol=!0)},"Nuevo Rol"),f("   "),e("a",{class:"btn btn-continue",id:"show-modal1",onClick:o[1]||(o[1]=l=>a.showCrearPermiso=!0)},"Nuevo Permiso"),f("   "),e("a",{class:"btn btn-search-blue",onClick:o[2]||(o[2]=l=>i.recargarTabla("General"))},ze)])])]),e("div",Je,[e("div",Qe,[e("div",We,[k(e("div",Ye,null,512),[[D,a.mostrarCarga]]),k(e("table",Ze,[es,e("tbody",null,[(t(!0),m(E,null,x(a.roles,l=>(t(),m("tr",{key:l.id},[ss,e("td",null,[e("p",os,u(l.id),1)]),e("td",null,[e("p",ts,u(l.name),1)]),e("td",rs,[e("div",as,[e("a",{class:"btn btn-alert btn-xs",title:"Edit",onClick:N=>i.rolEditar(l)},is,8,ls),f("   "),e("a",{class:"btn btn-closed btn-xs",title:"Delete",onClick:N=>i.deleteRole(l.id,l.name)},ms,8,ds)])])]))),128))])],512),[[D,a.mostrarTabla]]),us])])])]),hs,_(v,{ref:"confirmation"},null,512),_(g,{ref:"alert"},null,512),a.showEditarRol?(t(),w(r,{key:0,rol:a.roleditar,onClose:o[3]||(o[3]=l=>a.showEditarRol=!1),onRecarga:o[4]||(o[4]=l=>i.recargarTabla("General"))},null,8,["rol"])):$("",!0),a.showCrearRol?(t(),w(d,{key:1,onClose:o[5]||(o[5]=l=>a.showCrearRol=!1),onRecarga:o[6]||(o[6]=l=>i.recargarTabla("General"))})):$("",!0),a.showCrearPermiso?(t(),w(B,{key:2,onClose:o[7]||(o[7]=l=>a.showCrearPermiso=!1),onRecarga:o[8]||(o[8]=l=>i.recargarTabla("General"))})):$("",!0)])])}const $s=P(je,[["render",ps]]);export{$s as default};