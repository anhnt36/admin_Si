<?php
namespace SampleApp\Tests\Service;
use SampleApp\Service\PostService;
use SampleApp\Tests\Service\BaseServiceTest;

class PostServiceTest extends BaseServiceTest {
    public $dataInsertPost;
    public $dataInsertUser;
    public $dataInsertComment;
    public function setUp()
    {
        parent::setUp();
        $this->dataInsertUser = array(
            array(
                'name' => 'anhnguyen',
                'password' => '1234',
                'fullname' => 'Nguyễn Thế Anh',
                'sex' => 1,
                'created_Time' => date("Y-m-d H:i:s"),
                'updated_Time' => date("Y-m-d H:i:s")
            ),
        );
        $id = '';
        foreach($this->dataInsertUser as $dataUser) {
            $id = $this->app['user.service']->insert($dataUser);
        }
        $this->dataInsertUser[0]['id'] = $id;

        $this->dataInsertPost = array(
            array(
                'title' => 'TRỰC TIẾP MU - Club Brugge: MU lộ diện đội hình',
                'content' => 'MU kiếm bộn tiền nếu vượt qua vòng sơ loại Champions League
                Champions League luôn là mục tiêu của mọi CLB ở châu Âu, không chỉ vì chất lượng, truyền
                thống lâu đời mà còn vì số tiền kếch sù họ kiếm được từ bản quyền truyền hình cũng như phần thưởng từ UEFA.
                Daily Mail thống kê, nếu vượt qua vòng sơ loại, MU sẽ đút túi 8,53 triệu bảng. Ở những trận đấu tiếp theo
                tại vòng bảng và knock-out, các CLB sẽ nhận 710 nghìn bảng cho mỗi trận thắng và 360 nghìn bảng cho mội trận hòa,
                chưa kể khoảng thưởng riêng cho CLB vào chung kết.
                Tân binh chưa hài lòng với phong độ của MU
                "2 chiến thắng sau 2 vòng đấu là sự khởi đầu tuyệt vời tại Premier League, nhưng không vì thế mà MU hài lòng 100%.
                Đôi khi chiến thắng không có nghĩa rằng bạn đã chơi tốt, và tôi biết CLB còn phải cải thiện nhiều mặt nữa!", Morgan Schneiderlin phát biểu đầu thận trọng trong buổi họp báo trước trận đấu thuộc vòng sơ loại Champions League.

                Kể từ khi gia nhập đội chủ sân Old Trafford, cầu thủ người Pháp đã chơi tốt và chiếm một suất chính thức trong đội hình ra sân.',
                'id_user' => $this->dataInsertUser[0]['id'],
                'created_Time' => date("Y-m-d H:i:s"),
                'updated_Time' => date("Y-m-d H:i:s")
            ),

        );
        $id = '';
        foreach($this->dataInsertPost as $dataPost) {
            $id = $this->app['post.service']->insert($dataPost);
        }
        $this->dataInsertPost[0]['id'] = $id;

        $this->dataInsertComment = array(
            array(
                'id_user' => $this->dataInsertUser[0]['id'],
                'id_post' => $this->dataInsertPost[0]['id'],
                'content' => 'duyet',
                'created_Time' => date("Y-m-d H:i:s"),
                'updated_Time' => date("Y-m-d H:i:s")
            )
        );
        foreach($this->dataInsertComment as $dataComment) {
            $id = $this->app['post.service']->addComment($dataComment);
        }
        $this->dataInsertComment[0]['id'] = $id;
    }

    public function tearDown() {
        foreach($this->dataInsertPost as $dataPost) {
            $this->app['post.service']->delete($dataPost['id']);
        }
        foreach($this->dataInsertUser as $dataUser) {
            $this->app['user.service']->destroy($dataUser['name']);
        }
    }

    public function testGetAllPost() {
        $result = count($this->app['post.service']->getAll('1', '0'));
        $this->assertGreaterThanOrEqual(1, $result, 'Error : Error funciton GetAll (PostService)');

    }
    public function testGetAllComment() {
        $this->assertCount(1,$this->app['post.service']->getAllComment($this->dataInsertPost[0]['id']), 'Error : Error funciton GetAllComment (PostService)');
    }

    public function testEditPost() {
        $dataTest = array(
            array(
                'id' => $this->dataInsertPost[0]['id'],
                'title' => 'aaaaaaaaaaaa',
                'content' => 'aaaaaaaaaaaaaaa',
                'id_user' => $this->dataInsertUser[0]['id'],
                'created_Time' => date("Y-m-d H:i:s"),
                'updated_Time' => date("Y-m-d H:i:s")
            ),

        );

        foreach ($dataTest as $data) {
            $this->app['post.service']->edit($data);
        }
        $this->assertCount(1, $this->app['post.service']->getAll('1', '0'), 'Error : Error function Edit (PostService)');
        $result = $this->app['post.service']->getAll()[0]['title'];
        $this->assertSame($dataTest[0]['title'], $result, 'Error : Error function Edit (PostService)');
    }

    public function testGetData() {
        $this->assertCount(1, $this->app['post.service']->getData($this->dataInsertPost[0]['id']), 'Error : Error function getData (PostService)');
    }

}