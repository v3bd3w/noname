<?php

class Check
{

	static function ddgr_result(array $ddgr_result)
	{//{{{//
		$result = [];
		foreach($ddgr_result as $key => $item) {
			if(!(
				@is_string($item["abstract"])
				&& @is_string($item["title"])
				&& @is_string($item["url"])
			)){}
		}
	}//}}}//

}

