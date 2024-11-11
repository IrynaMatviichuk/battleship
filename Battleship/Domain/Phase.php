<?php

namespace Battleship\Domain;

enum Phase: string
{
    case PLACE_SHIPS = 'place_ships';
    case BATTLE = 'battle';
}
