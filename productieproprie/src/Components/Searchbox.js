import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Paper from '@material-ui/core/Paper';
import InputBase from '@material-ui/core/InputBase';
import IconButton from '@material-ui/core/IconButton';
import Divider from '@material-ui/core/Divider';
import SearchIcon from '@material-ui/icons/Search';
import CheckCircleOutlineIcon from '@material-ui/icons/CheckCircleOutline';
import EditLocationIcon from '@material-ui/icons/EditLocation';

const useStyles = makeStyles((theme) => ({
  input: {
    marginLeft: theme.spacing(1),
    flex: 1,
  },
  divider: {
    height: 28,
    margin: 4,
  }
}));

const Searchbox = () =>{
  const classes = useStyles();

  return (
    <section className="searchBox d-flex align-items-center justify-content-center">
      <Paper component="form" className="searchField">
        <IconButton className="iconButton" aria-label="menu">
          <SearchIcon />
        </IconButton>
        <InputBase
          className={classes.input}
          placeholder="Search Google Maps"
          inputProps={{ 'aria-label': 'search google maps' }}
        />
        <Divider className={classes.divider} orientation="vertical" />
        <IconButton type="submit" className="iconButton" aria-label="search">
          <EditLocationIcon />
          <small>Locatie</small>
        </IconButton>
        <Divider className={classes.divider} orientation="vertical" />
        <IconButton type="submit" className="iconButton" aria-label="search">
          <CheckCircleOutlineIcon className="mr-2" />
          <small>Cauta</small>
        </IconButton>
      </Paper>
    </section>
  )
}

export default Searchbox;