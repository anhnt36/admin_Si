<?php
namespace SampleApp\Tests\Controller;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use SampleApp\Tests\Controller\BaseControllerTest;
use Silex\WebTestCase;

class PostControllerTest extends BaseControllerTest {
    public $dataInsertPost;
    public $dataInsertComment;
    public function __construct() {
        parent::__construct();
    }

    public function setUp() {
        parent::setUp();
        $id_user = $this->app['user.service']->checkUser($this->dataInsertUser[0]);
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
                'id_user' => $id_user,
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

    public function  tearDown() {
        parent::tearDown();
        foreach($this->dataInsertPost as $dataPost) {
            $this->app['post.service']->delete($dataPost['id']);
        }
    }

    public function testIndexDisplayListPosts()
    {
        $crawler = $this->client->request('GET', '/posts/');

        $controls = [
            'a:contains("Add Post")',
            "a[href='../posts/{$this->dataInsertPost[0]['id']}']",
            "a[href='../posts/destroy/{$this->dataInsertPost[0]['id']}']",
            "a[href='../posts/update/{$this->dataInsertPost[0]['id']}']",
        ];
        $this->assertCountFilter($controls, $crawler);
    }

    public function testIndexClickAddPost() {
        $crawler = $this->client->request('GET', '/posts/');

        $buttonAddPost = $crawler->selectLink('Add Post')->link();

        $crawler = $this->client->click($buttonAddPost);

        $this->assertEquals($this->app['url_generator']->generate('AddPost'),
            $this->client->getRequest()->getRequestUri()
        );
    }

    public function testIndexClickUpdatePost() {
        $crawler = $this->client->request('GET', "/posts/");

        $buttonUpdatePost = $crawler->selectLink('Update')->link();
        $crawler = $this->client->click($buttonUpdatePost);

        $this->assertEquals($this->app['url_generator']->generate('UpdatePost',array('id' => $this->dataInsertPost[0]['id'])),
            $this->client->getRequest()->getRequestUri()
        );
    }


    public function testIndexClickDeletePost() {
        $crawler = $this->client->request('GET', "/posts/");

        $buttonUpdatePost = $crawler->selectLink('Delete')->link();
        $crawler = $this->client->click($buttonUpdatePost);

        $this->assertEquals($this->app['url_generator']->generate('DeletePost',array('id' => $this->dataInsertPost[0]['id'])),
            $this->client->getRequest()->getRequestUri()
        );
    }

    public function testViewDisplayPost() {
        $crawler = $this->client->request('GET', "/posts/{$this->dataInsertPost[0]['id']}");
        $controls = [
            "h2:contains('{$this->dataInsertPost[0]['title']}')",
            "p[class='lead']",
            "a:contains('Quay lại trang trước')",
            "div.media-body",
//            "h4:contains('{$this->dataInsertComment[0]['content']}')"
        ];
        $this->assertCountFilter($controls, $crawler);
        $this->assertContains(
            "{$this->dataInsertComment[0]['content']}",
            $this->client->getResponse()->getContent(),
            'Error : Not display content comment'
        );
    }

    public function testDisplayFormAddPost () {
        $crawler = $this->client->request('GET', '/posts/new');

        $controls = [
            "form.form-horizontal",
            'h3:contains("Add Post")',
            'html:contains("Add")'
        ];

        $this->assertCountFilter($controls, $crawler);

    }

    public function testFormAddPostWithDataBlank() {
        $crawler = $this->client->request('GET', '/posts/new');
        $form = $crawler->selectButton('Add')->form();
        $form['AddUserForm[title]'] = '';
        $form['AddUserForm[content]'] = '';
        $crawler = $this->client->click($form);

        $controls = [
            'html:contains("Title should not be blank")',
            'html:contains("Content should not be blank")'
        ];
        $this->assertCountFilter($controls, $crawler);

    }

//    public function testFormAddPostWithDataNotBlank() {
//        $crawler = $this->client->request('GET', '/posts/new');
//
//        $form = $crawler->selectButton('Add')->form();
//        $form['AddUserForm[title]'] = 'aaaaa';
//        $form['AddUserForm[content]'] = 'aaaaa';
//        $crawler = $this->client->submit($form);
//        $this->assertTrue(
//            $this->client->getResponse()->isRedirect('../posts/')
//        );
//    }


    public function testUpdateWithDataBlank () {
        $crawler = $this->client->request('GET', "/posts/update/{$this->dataInsertPost[0]['id']}");

        $controls = [
            'h3:contains("Update Post")',
            'html:contains("Update")',
            "input[value='{$this->dataInsertPost[0]['title']}']",
        ];
        $this->assertCountFilter($controls, $crawler);

        $form = $crawler->selectButton('Update')->form();
        $form['AddUserForm[title]'] = '';
        $form['AddUserForm[content]'] = '';
        $crawler = $this->client->submit($form);

        $controls = [
            'html:contains("Title should not be blank")',
            'html:contains("Content should not be blank")',
        ];
        $this->assertCountFilter($controls, $crawler);
    }

    public function testUpdateWithDataNotBlank () {
        $crawler = $this->client->request('GET', "/posts/update/{$this->dataInsertPost[0]['id']}");

        $form = $crawler->selectButton('Update')->form();
        $form['AddUserForm[title]'] = '1234';
        $form['AddUserForm[content]'] = '1234';
        $crawler = $this->client->submit($form);
        $this->assertTrue(
            $this->client->getResponse()->isRedirect('../../posts/')
        );
    }

    public function testDisplayFormUpdatePost() {
        $crawler = $this->client->request('GET', "/posts/update/{$this->dataInsertPost[0]['id']}");

        $controls = [
            'h3:contains("Update Post")',
            'html:contains("Update")',
            "input[value='{$this->dataInsertPost[0]['title']}']",
        ];
        $this->assertCountFilter($controls, $crawler);
    }

    public function testAddCommentWithData() {
        $crawler = $this->client->request('GET', "/posts/{$this->dataInsertPost[0]['id']}");

        $form = $crawler->selectButton('Submit')->form();
        $content = 'testaaaaaaaaaaaaaa';
        $form->setValues(
            array(
                'AddCommentForm[content]'   => $content,
                'AddCommentForm[id_post]'   => "{$this->dataInsertPost[0]['id']}",
                'AddCommentForm[id_user]'   => "{$this->dataInsertUser[0]['id']}"
            )
        );
        $crawler = $this->client->submit($form);
        $this->assertTrue(
            $this->client->getResponse()->isRedirect("../posts/{$this->dataInsertPost[0]['id']}")
        );
    }

}

?>