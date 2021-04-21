<?php


namespace App\Tests\Integration;


use App\Command\RandomuserImportCommand;
use App\Entity\Customer;
use App\Http\RandomuserClient;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RandomuserImportCommandTest
 * @package Unit
 */
class RandomuserImportCommandTest extends DatabaseDependantTestCase
{
    /** @test */
    public function test_import_command_behaves_correctly()
    {
        $application = new Application(self::$kernel);
        $randomuserClient = $this->createMock(RandomuserClient::class);
        $randomuserClient->expects($this->once())
            ->method('fetchCustomers')
            ->willReturn($this->getContent());
        $customerManager = static::$kernel->getContainer()->get('customer-manager');
        $serializer = static::$kernel->getContainer()->get('serializer');
        $application->add(new RandomuserImportCommand($randomuserClient, $customerManager, $serializer));
        $command = $application->find('app:randomuser-import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $repository = $this->entityManager->getRepository(Customer::class);
        $customer = $repository->findAll();
        $this->assertEquals(3, count($customer));
    }


    /** @test */
    public function test_import_command_behaves_correctly_when_api_error()
    {
        $application = new Application(self::$kernel);
        $randomuserClient = $this->createMock(RandomuserClient::class);

        $randomuserClient->expects($this->once())
            ->method('fetchCustomers')
            ->willReturn(new JsonResponse([], 200));

        $customerManager = static::$kernel->getContainer()->get('customer-manager');
        $serializer = static::$kernel->getContainer()->get('serializer');

        $application->add(new RandomuserImportCommand($randomuserClient, $customerManager, $serializer));

        $command = $application->find('app:randomuser-import');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $repository = $this->entityManager->getRepository(Customer::class);

        $customer = $repository->findAll();

        $this->assertEquals(0, count($customer));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function getContent()
    {
        $threeCustomers = json_decode('{"results":[{"gender":"female","name":{"title":"Miss","first":"Ellen","last":"Fletcher"},"location":{"street":{"number":4077,"name":"E North St"},"city":"Hervey Bay","state":"Australian Capital Territory","country":"Australia","postcode":1039,"coordinates":{"latitude":"87.1122","longitude":"61.6508"},"timezone":{"offset":"+2:00","description":"Kaliningrad, South Africa"}},"email":"ellen.fletcher@example.com","login":{"uuid":"44386370-d511-49c5-9555-6c5680e63d69","username":"goldengoose827","password":"emmett","salt":"thfJWwYw","md5":"ec207a8a6ed845c7714507a643e0b14e","sha1":"1b61ce6667ec8346cf378b915976909d5de4d823","sha256":"bc44a212fc96b6da42238105b8b017b9d8c796ad7dc2db3cb34e54280e120c46"},"dob":{"date":"1963-09-17T01:03:43.667Z","age":58},"registered":{"date":"2009-10-29T06:17:21.172Z","age":12},"phone":"00-8318-4620","cell":"0448-068-695","id":{"name":"TFN","value":"535203101"},"picture":{"large":"https://randomuser.me/api/portraits/women/49.jpg","medium":"https://randomuser.me/api/portraits/med/women/49.jpg","thumbnail":"https://randomuser.me/api/portraits/thumb/women/49.jpg"},"nat":"AU"},{"gender":"male","name":{"title":"Mr","first":"Gregory","last":"Stanley"},"location":{"street":{"number":350,"name":"Robinson Rd"},"city":"Tamworth","state":"Tasmania","country":"Australia","postcode":8012,"coordinates":{"latitude":"-41.6410","longitude":"-174.8914"},"timezone":{"offset":"+11:00","description":"Magadan, Solomon Islands, New Caledonia"}},"email":"gregory.stanley@example.com","login":{"uuid":"6d1bdae8-8594-4141-8214-16df0bda6f65","username":"purplefrog655","password":"slider","salt":"9zwTKePV","md5":"917b5e80a30c9228cd0c3fe58073703a","sha1":"dcd40c7a92022ae21fadcbc40026a574005d9579","sha256":"41b455122f488317e2f5c694885a05979af239ed902ba1ad0c732935c4ff8e6e"},"dob":{"date":"1978-04-10T08:51:51.648Z","age":43},"registered":{"date":"2017-01-09T16:47:16.915Z","age":4},"phone":"01-7319-6977","cell":"0452-955-386","id":{"name":"TFN","value":"662600479"},"picture":{"large":"https://randomuser.me/api/portraits/men/59.jpg","medium":"https://randomuser.me/api/portraits/med/men/59.jpg","thumbnail":"https://randomuser.me/api/portraits/thumb/men/59.jpg"},"nat":"AU"},{"gender":"female","name":{"title":"Ms","first":"Teresa","last":"Beck"},"location":{"street":{"number":2184,"name":"Marsh Ln"},"city":"Launceston","state":"South Australia","country":"Australia","postcode":6386,"coordinates":{"latitude":"43.3108","longitude":"31.3933"},"timezone":{"offset":"+4:00","description":"Abu Dhabi, Muscat, Baku, Tbilisi"}},"email":"teresa.beck@example.com","login":{"uuid":"1acda599-d92c-452f-aebc-84f885d9aa3a","username":"tinyzebra513","password":"waffle","salt":"V6r2b1uR","md5":"6fd9c3ef1370f5eced36beb662b5e070","sha1":"e64ffaf90098c132e716e7d7caa00c525cea15ee","sha256":"4e93c022325fcade680f7d1bb6fe342a505db001d908557eb4df01f26ed10a8e"},"dob":{"date":"1951-12-12T01:25:02.969Z","age":70},"registered":{"date":"2019-03-24T07:51:16.265Z","age":2},"phone":"09-5830-7872","cell":"0407-307-097","id":{"name":"TFN","value":"692341252"},"picture":{"large":"https://randomuser.me/api/portraits/women/45.jpg","medium":"https://randomuser.me/api/portraits/med/women/45.jpg","thumbnail":"https://randomuser.me/api/portraits/thumb/women/45.jpg"},"nat":"AU"}],"info":{"seed":"ee0d23fc2161a9c7","results":3,"page":1,"version":"1.3"}}', true);
        return new JsonResponse($threeCustomers['results'], Response::HTTP_OK);
    }
}