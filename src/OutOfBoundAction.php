<?php
namespace Krishna\DataValidator;

enum OutOfBoundAction: int {
	case Trim = 0;
	case Keep = 1;
	case Error = 2;
}