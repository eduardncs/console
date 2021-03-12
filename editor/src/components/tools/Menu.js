import React, { useState, useEffect } from 'react';
import { Modal , Button, Container, ListGroup, Dropdown, DropdownButton } from 'react-bootstrap';
import $ from 'jquery';
import menuLogo from './../../images/menu.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faHome, faFileAlt, faFolder, faFolderPlus, faEllipsisH } from '@fortawesome/free-solid-svg-icons';
import Sortable from 'sortablejs/modular/sortable.complete.esm.js';
import { etoast, ID } from './../globals';
import { postMan, domain, handleResponse } from './../backend';
import { User , Project } from '../data';

const sortables = {
  value: null,
  get function() { return this.value; },
  set function(val) {
      Array.prototype.isArray(this.value) ? this.value.push(val) : this.value = val;
  },
  add: (val) => {
      Array.prototype.isArray(this.value) ? this.value.push(val) : this.value = val;
  }
}

const changeIndex = async (who, neighbour, inverted) => {
  const sandbox = $("#sandbox");
  let rawItem = sandbox.contents().find("nav ." + who).detach();
  if (rawItem.length === 0) { console.error("Raw item was not found!!!"); return; };
  postMan(
    "processors/editor.backend.req.php",
    'get',
    'changeIndex',
    {
      "UserID": User.getUser.id,
      "ProjectID": Project.getProject.project_id,
      "Key": who,
      "Neighbour": neighbour,
      "Inverted": inverted,
    }
  ).then((response) =>{
    handleResponse(response);
      !inverted ? sandbox.contents().find("." + neighbour).after(rawItem) 
      : sandbox.contents().find("." + neighbour).before(rawItem)
  })
}

const move = async (who, to, neighbour, inverted, isFirst) => {
  let rawItem = $("#sandbox").contents().find("nav ." + who).detach();
  if (rawItem.length === 0) { console.error("Raw item was not found!!!"); return; }
  
  $.ajax({
      url: domain+'clients/' + User.getUser.Business_Name + '/' + Project.getProject.project_name_short + '/core/data.php',
      dataType: "json",
      success: (data) => {
          let item;
          to === "menu-container-master" ? item = $(data['MENU_BASE'][1]) :
              item = $(data['MENU_TREE'][1]);
          if (isFirst) {
              $("#sandbox").contents().find("." + neighbour + " .dropdown-menu").append(item);
          } else {
              !inverted ? $("#sandbox").contents().find("." + neighbour).after(item) :
                  $("#sandbox").contents().find("." + neighbour).before(item)
          }
          let anchor = item.find("a");
          let exanchor = rawItem.find("a");

          if (exanchor.length === 0)
              exanchor = rawItem; //rawITem is the exanchor
          if (anchor.length === 0)
              anchor = item; //item is the anchor
          item.removeClass(item.data("link"));
          item.attr("data-link", who);
          item.addClass(who);
          anchor.attr("href", exanchor.attr("href"));
          anchor.text(exanchor.text());
          anchor.attr("target", exanchor.attr("target"));
          // Now make the ajax call to backend
          postMan(
            "processors/editor.backend.req.php",
            'get',
            'move',
            {
              "UserID": User.getUser.id,
              "ProjectID": Project.getProject.project_id,
              "Key":who,
              "To":to,
              "Neighbour":neighbour,
              "Inverted":inverted,
              "isFirst":isFirst
            }
          ).then((response) =>{
            handleResponse(response);
          })
      }
  })
}

const getMenuFromBackend = async() =>{
  return await postMan(
    "processors/editor.backend.req.php",
    "get",
    "fetchMenuJSON",
    {
      "UserID":User.getUser.id,
      "ProjectID":Project.getProject.project_id
    }).then((data)=>{
      const menu = data.Menu;
      const menuStructure = [];
      menu.forEach(item => {
        if(item['P_Key'] === "0")
        {
          menuStructure.push({
              key : item['Key'],
              text: item['Text'],
              href: item['Href'],
              target: item['Target']}
          )
        }else{
          const parent = {
            key : item['Key'],
            text: item['Text'],
            children: []
          };
          item['Children'].forEach(chield =>{
            parent.children.push({ 
                key : chield['Key'],
                text: chield['Text'],
                href: chield['Href'],
                target: chield['Target']})
          })
          menuStructure.push(parent);
        }
      });
      return menuStructure;
    }).catch((error)=>{
      return [];
    })
}

const Menu = () =>{
    const [show, setShow] = useState(true);
    const [menuItems, setMenuItems] = useState([]);
    const handleClose = () => setShow(false);
    const reloadMenu = () => setMenuItems( <FetchMenu/> ) ;
    useEffect(() =>{
      setMenuItems( <FetchMenu/> )
    },[])

    const FetchMenu = () =>{

      const [items , setItems] = useState([]);
      useEffect(() =>{
        getMenuFromBackend().then(
          (response) => {
            setItems(response);
          }
        )
      },[]);
      useEffect(()=>{
        if(items.length === 0) return;
          const nestedSortables = $(".nestedSortable");
          for (let i = 0; i < nestedSortables.length; i++) {
              sortables.add = new Sortable(nestedSortables[i], {
                  group: 'nested',
                  ghostClass: 'active',
                  animation: 150,
                  fallbackOnBody: true,
                  onUpdate: (event) => {
                      let inverted = false;
                      let neighbour = $(event.item).prev();
                      if (neighbour.length === 0) {
                          neighbour = $(event.item).next();
                          inverted = true;
                      }
                      if (neighbour.length === 0) { console.error("This element has no neighbours"); return; }
                      changeIndex($(event.item).data("link"), neighbour.data("link"), inverted);
                  },
                  onAdd: (event) => {
                      if ($(event.item).data("isfolder") === true) {
                          etoast("FE: Folders cannot be moved inside other folders yet ...");
                          return;
                      }
                      let oldParent = $(event.from).data("parent");
                      let newParent = $(event.to).data("parent");
                      if (typeof oldParent === typeof undefined)
                          oldParent = "menu-container-master";
                      if (typeof newParent === typeof undefined)
                          newParent = "menu-container-master";
                      let neighbour = $(event.item).prev();
                      let inverted = false;
                      let isFirst = false;
                      if (neighbour.length === 0) {
                          neighbour = $(event.item).next();
                          inverted = true;
                      }
                      if (neighbour.length === 0) {
                          isFirst = true;
                          neighbour = $(event.item).parent().parent();
                      }
                      move($(event.item).data("link"), newParent, neighbour.data("link"), inverted, isFirst);
                  }
              });
          }
      },[items])

      return (
        <ListGroup key="unique" className="nestedSortable">
          {
            items.map((item, index)=>{
              if(typeof item.children !== typeof undefined){
                return(
                  <ListGroup.Item style={{cursor:"move"}} className={`menu-item ${item.key}`} key={index} data-link={item.key}>
                    <div key={index} className="mb-2">
                    <FontAwesomeIcon className="mr-2" icon={ faFolder } />
                      {item.text}
                      <DropdownButton
                      className="float-right e-caret-hide"
                      variant="white"
                      menuAlign="left"
                      size="sm"
                      title={ <FontAwesomeIcon icon={faEllipsisH} /> }
                      id={`btn_${item.key}`}
                    >
                      <Dropdown.Item eventKey="1" onClick={ () => { editLink(item.key) } }>Edit</Dropdown.Item>
                      <Dropdown.Item eventKey="2" onClick={ () => { removeLink(item.key) } }>Remove</Dropdown.Item>
                    </DropdownButton>
                    </div>
                    <ListGroup className="nestedSortable" key={Math.floor(Math.random() * 10873)+1} data-link={item.key}>
                    {
                      item.children.map((chield,indx)=>{
                        return (
                          <ListGroup.Item style={{cursor:"move"}} className={`menu-item ${chield.key}`} key={indx} data-link={chield.key}>
                            <FontAwesomeIcon className="mr-2" icon={ faFileAlt } />
                            {
                              chield.text
                            }
                          <DropdownButton
                            className="float-right e-caret-hide"
                            variant="white"
                            menuAlign="left"
                            size="sm"
                            title={ <FontAwesomeIcon icon={faEllipsisH} /> }
                            id={`btn_${item.key}`}
                          >
                            <Dropdown.Item eventKey="1" onClick={ () => { editLink(chield.key) } }>Edit</Dropdown.Item>
                            <Dropdown.Item eventKey="2" onClick={ () => { removeLink(chield.key) } }>Remove</Dropdown.Item>
                          </DropdownButton>
                          </ListGroup.Item>
                        )
                      })
                    }
                    </ListGroup>
                  </ListGroup.Item>
                )
              }else{
                if(index === 0)
                {
                  return(
                    <ListGroup.Item style={{cursor:"move"}} className={`menu-item ${item.key}`} key={index} data-link={item.key}>
                      <FontAwesomeIcon className="mr-2" icon={ faHome } />
                      { item.text }
                      <DropdownButton
                        className="float-right e-caret-hide"
                        variant="white"
                        menuAlign="left"
                        size="sm"
                        title={ <FontAwesomeIcon icon={faEllipsisH} /> }
                        id={`btn_${item.key}`}
                      >
                        <Dropdown.Item eventKey="1" onClick={ () => { editLink(item.key) } }>Edit</Dropdown.Item>
                        <Dropdown.Item eventKey="2" onClick={ () => { removeLink(item.key) } }>Remove</Dropdown.Item>
                      </DropdownButton>
                    </ListGroup.Item>
                  )
                }else{
                  return(
                  <ListGroup.Item style={{cursor:"move"}} className={`menu-item ${item.key}`} key={index} data-link={item.key}>
                    <FontAwesomeIcon className="mr-2" icon={ faFileAlt } />
                    { item.text }
                    <DropdownButton
                      className="float-right e-caret-hide"
                      variant="white"
                      menuAlign="left"
                      size="sm"
                      title={ <FontAwesomeIcon icon={faEllipsisH} /> }
                      id={`btn_${item.key}`}
                    >
                      <Dropdown.Item eventKey="1" onClick={ () => { editLink(item.key) } }>Edit</Dropdown.Item>
                      <Dropdown.Item eventKey="2" onClick={ () => { removeLink(item.key) } }>Remove</Dropdown.Item>
                    </DropdownButton>
                  </ListGroup.Item>
                  )
                }
              }
            })
          }
        </ListGroup>
      );
    }


    const editLink = (i) =>{
      console.log(i);
    }

    const removeLink = async (i) =>{
      postMan(
        "processors/editor.backend.req.php",
        'get',
        'removeLink',
        {
          "UserID":User.getUser.id,
          "ProjectID":Project.getProject.project_id,
          "Key": i
        }
      ).then((response) => {
        handleResponse(response)
        $("#sandbox").contents().find("."+i).remove();
        $("#menu-container-master").find("."+i).remove();
        })
    }
    
    const addFolder = async() =>{
      console.log("addFolder");
      const id = ID();
      await postMan(
        "processors/editor.backend.req.php",
        "get",
        "addLink",
        {
          "UserID":User.getUser.id,
          "ProjectID":Project.getProject.project_id,
          "Key":id,
          "isFolder":true
        }
      ).then( async (response) =>{
        handleResponse(response);
        reloadMenu();
        $.ajax({
          url: domain+'clients/' + User.getUser.Business_Name + '/' + Project.getProject.project_name_short + '/core/data.php',
          dataType: "json",
          success:(data) =>{
            const item = $(data['MENU_TREE'][0]);
            $("#sandbox").contents().find("._R3xd13dsc").append(item);
            const anchor = item.find("a");
            item.removeClass(item.data("link"));
            item.attr("data-link", id);
            item.addClass(id);
            anchor.attr("href", "#");
            anchor.text("New folder");
            $("#sandbox").contents().find("._R3xd13dsc").append($(data['MENU_TREE'][2]));
          }
        })
      }
      );
    }

    const addLink = async () =>{
      const id = ID();
      postMan(
        "processors/editor.backend.req.php",
        "get",
        "addLink",
        {
          "UserID":User.getUser.id,
          "ProjectID":Project.getProject.project_id,
          "Key":id,
          "isFolder":false
        }
      ).then( (response) =>{
        handleResponse(response);
        reloadMenu();
        $.ajax({
          url: domain+'clients/' + User.getUser.Business_Name + '/' + Project.getProject.project_name_short + '/core/data.php',
          dataType: "json",
          success:(data) =>{
            const item = $(data['MENU_BASE'][1]);
            $("#sandbox").contents().find("._R3xd13dsc").append(item);
            const anchor = item.find("a");
            item.removeClass(item.data("link"));
            item.attr("data-link", id);
            item.addClass(id);
            anchor.attr("href", "#");
            anchor.text("New link");
            anchor.attr("target", "_self");
          }
        })}
      );
    }

    return (
      <Modal className="left" show={show} onHide={handleClose} animation={true} backdrop="static">
        <Modal.Header closeButton>
          <Modal.Title>Site main menu</Modal.Title>
        </Modal.Header>
        <Modal.Body className="p-0">
          <img src={menuLogo} width="100%" height="150px" alt="Menu logo" />
          <Container id="menu-container-master">
            { menuItems }
          </Container>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={addFolder}>
            <FontAwesomeIcon className="mr-2" icon={faFolderPlus} />
            Add folder
          </Button>
          <Button variant="primary" onClick={addLink}>
          <FontAwesomeIcon className="mr-2" icon={faFileAlt} />
            Add link
          </Button>
        </Modal.Footer>
      </Modal>
    );
  }

export default Menu;