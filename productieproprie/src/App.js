import React from 'react';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import Home from './Home';
import Login from './Login';
import Register from './Register';
import Add from './Add';
import { getUser } from './Components/User';
import './App.css';

export const UserContext = React.createContext();

const App = () =>{
  return (
    <>
    <Router>
      <Switch>
        <Route path="/" exact component={Home} />
        <Route path="/login" component={Login} />
        <Route path="/register" component={Register} />
        <UserContext.Provider value={ getUser() }>
          <Route path="/add" component={Add} />
        </UserContext.Provider>
      </Switch>
    </Router>
    </>
  );
}

export default App;
